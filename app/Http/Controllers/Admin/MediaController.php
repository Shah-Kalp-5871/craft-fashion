<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaController extends Controller
{
    private ImageManager $imageManager;

    public function __construct()
    {
         if (class_exists(Driver::class)) {
            $this->imageManager = new ImageManager(new Driver());
        }
    }

    public function index()
    {
        return view('admin.media.index');
    }

    /**
     * Get Media Data for Modals/AJAX
     */
    public function getData(Request $request)
    {
        try {
            $query = Media::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('file_name', 'LIKE', "%{$search}%")
                      ->orWhere('alt_text', 'LIKE', "%{$search}%");
            }
            
            $query->latest();

            $perPage = $request->input('per_page', 12);
            $media = $query->paginate($perPage);

            $items = $media->getCollection()->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'url' => asset(Storage::url($item->file_path)),
                    'thumbnail_url' => $item->thumbnails && isset($item->thumbnails['small']) 
                        ? asset(Storage::url($item->thumbnails['small'])) 
                        : asset(Storage::url($item->file_path)),
                    'file_path' => $item->file_path,
                    'file_name' => $item->file_name,
                    'mime_type' => $item->mime_type,
                    'file_size' => $item->file_size,
                    'size_formatted' => $this->formatBytes($item->file_size),
                    'alt_text' => $item->alt_text,
                    'created_at_formatted' => $item->created_at->format('M d, Y'),
                    'is_image' => $item->mime_type ? str_starts_with($item->mime_type, 'image/') : false,
                ];
            });

            $paginationData = $media->toArray();
            $paginationData['data'] = $items;

            return response()->json([
                'success' => true,
                'data' => $paginationData
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Media Load Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading media: ' . $e->getMessage()
            ], 500);
        }
    }

    public function upload(Request $request)
    {
        $request->validate([
             'files.*' => 'required|file|max:10240', // 10MB max per file
        ]);

        if (!$request->hasFile('files')) {
            return response()->json(['success' => false, 'message' => 'No files uploaded'], 400);
        }

        $uploadedMedia = [];
        $errors = [];

        foreach ($request->file('files') as $file) {
            try {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                $uniqueName = Str::slug($fileName) . '_' . time() . '_' . Str::random(5) . '.' . $extension;
                
                $storagePath = 'products/media/' . date('Y/m');
                $fullPath = $storagePath . '/' . $uniqueName;
                
                Storage::disk('public')->putFileAs($storagePath, $file, $uniqueName);
                
                // Create thumbnails if image
                $thumbnails = [];
                if (str_starts_with($file->getMimeType(), 'image/') && isset($this->imageManager)) {
                    $thumbnails = $this->createThumbnails($file, $storagePath, $uniqueName);
                }

                $media = Media::create([
                    'file_name' => $originalName,
                    'file_path' => $fullPath,
                    'disk' => 'public',
                    'mime_type' => $file->getMimeType(),
                    'file_type' => str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'document',
                    'file_size' => $file->getSize(),
                    'thumbnails' => $thumbnails ?: null,
                    'metadata' => [
                        'original_name' => $originalName,
                        'extension' => $extension,
                    ],
                    'uploaded_by' => auth()->id(), 
                    'uploader_type' => 'admin',
                ]);

                $uploadedMedia[] = [
                    'id' => $media->id,
                    'url' => asset(Storage::url($fullPath)),
                    'file_name' => $originalName
                ];

            } catch (\Exception $e) {
                $errors[] = "Failed to upload {$file->getClientOriginalName()}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => count($errors) === 0,
            'data' => count($uploadedMedia) > 0 ? $uploadedMedia[0] : null, // Backwards compatibility for single select UI
            'all_uploaded' => $uploadedMedia,
            'errors' => $errors
        ]);
    }

    public function show($id)
    {
        $media = Media::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $media->id,
                'url' => asset(Storage::url($media->file_path)),
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'file_size' => $media->file_size,
                'size_formatted' => $this->formatBytes($media->file_size),
                'alt_text' => $media->alt_text,
                'created_at_formatted' => $media->created_at->format('M d, Y'),
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
        ]);

        $media->update([
            'alt_text' => $request->alt_text,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully',
            'data' => $media
        ]);
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($media->file_path)) {
                Storage::disk('public')->delete($media->file_path);
            }

            // Delete thumbnails
            if ($media->thumbnails) {
                foreach ($media->thumbnails as $thumb) {
                    if (Storage::disk('public')->exists($thumb)) {
                        Storage::disk('public')->delete($thumb);
                    }
                }
            }

            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Media deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete media: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media,id'
        ]);

        $medias = Media::whereIn('id', $request->ids)->get();
        $count = 0;

        foreach ($medias as $media) {
            try {
                if (Storage::disk('public')->exists($media->file_path)) {
                    Storage::disk('public')->delete($media->file_path);
                }

                if ($media->thumbnails) {
                    foreach ($media->thumbnails as $thumb) {
                        if (Storage::disk('public')->exists($thumb)) {
                            Storage::disk('public')->delete($thumb);
                        }
                    }
                }

                $media->delete();
                $count++;
            } catch (\Exception $e) {
                // Continue with others
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully deleted $count file(s)",
            'data' => ['deleted_count' => $count]
        ]);
    }

    private function formatBytes($bytes, $decimals = 2)
    {
        if ($bytes === 0) return '0 Bytes';
        $k = 1024;
        $dm = $decimals < 0 ? 0 : $decimals;
        $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), $dm) . ' ' . $sizes[$i];
    }

    private function createThumbnails($file, $storagePath, $fileName)
    {
        $thumbnails = [];
        try {
             $image = $this->imageManager->read($file->getRealPath());
             $originalName = pathinfo($fileName, PATHINFO_FILENAME);
             $extension = pathinfo($fileName, PATHINFO_EXTENSION);

             // Small
             $smallName = $originalName . '_small.' . $extension;
             $smallImage = clone $image;
             $smallImage->cover(150, 150);
             Storage::disk('public')->put($storagePath . '/' . $smallName, (string) $smallImage->encodeByExtension($extension));
             $thumbnails['small'] = $storagePath . '/' . $smallName;

        } catch(\Exception $e) {
            // Squelch image error to ensure primary upload succeeds
        }
        
        return $thumbnails;
    }
}
