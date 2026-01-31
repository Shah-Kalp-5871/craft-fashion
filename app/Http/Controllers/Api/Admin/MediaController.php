<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\MediaRequest;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
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
        $this->imageManager = new ImageManager(new Driver());
    }



    /**
     * Global response structure
     */
    private function apiResponse($success = true, $data = null, $message = '', $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * Display a listing of media files.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Get query parameters
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            $search = $request->get('search', '');
            $allowedSortColumns = [
                'created_at',
                'file_name',
                'file_size',
                'mime_type',
            ];

            $allowedDirections = ['asc', 'desc'];

            $sortBy = $request->get('sort_by', 'created_at');
            $sortDir = strtolower($request->get('sort_dir', 'desc'));

            // Validate column
            if (!in_array($sortBy, $allowedSortColumns)) {
                $sortBy = 'created_at';
            }

            // Validate direction
            if (!in_array($sortDir, $allowedDirections)) {
                $sortDir = 'desc';
            }


            // Build query
            $query = Media::query();

            // Apply search
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('file_name', 'LIKE', "%{$search}%")
                        ->orWhere('alt_text', 'LIKE', "%{$search}%")
                        ->orWhere('mime_type', 'LIKE', "%{$search}%");
                });
            }

            // Apply sorting
            $query->orderBy($sortBy, $sortDir);

            // Paginate results
            $media = $query->paginate($perPage, ['*'], 'page', $page);

            // Transform data for frontend
            $transformedData = $media->getCollection()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'file_name' => $item->file_name,
                    'file_path' => $item->file_path,
                    'url' => asset(Storage::url($item->file_path)),
                    'thumbnail_url' => $item->thumbnails ? asset(Storage::url($item->thumbnails['small'] ?? $item->file_path)) : asset(Storage::url($item->file_path)),
                    'mime_type' => $item->mime_type,
                    'file_type' => $item->file_type,
                    'file_size' => $item->file_size,
                    'size_formatted' => $this->formatBytes($item->file_size),
                    'alt_text' => $item->alt_text,
                    'created_at' => $item->created_at,
                    'created_at_formatted' => $item->created_at->format('M d, Y H:i'),
                    'updated_at' => $item->updated_at,
                    'uploaded_by' => auth('admin_api')->id() ?? 0,
                    'metadata' => $item->metadata,
                ];
            });

            return $this->apiResponse(true, [
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $media->currentPage(),
                    'from' => $media->firstItem(),
                    'to' => $media->lastItem(),
                    'per_page' => $media->perPage(),
                    'total' => $media->total(),
                    'last_page' => $media->lastPage(),
                ],
                'links' => [
                    'first' => $media->url(1),
                    'last' => $media->url($media->lastPage()),
                    'prev' => $media->previousPageUrl(),
                    'next' => $media->nextPageUrl(),
                ],
            ], 'Media list retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Media index error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve media list', 500);
        }
    }

    /**
     * Upload media files.
     */
    public function upload(MediaRequest $request): JsonResponse
    {
        try {
            $uploadedFiles = [];
            $failedFiles = [];

            if (!$request->hasFile('files')) {
                return $this->apiResponse(false, null, 'No files uploaded', 400);
            }

            foreach ($request->file('files') as $file) {
                try {
                    // Validate file
                    if (!$file->isValid()) {
                        $failedFiles[] = [
                            'name' => $file->getClientOriginalName(),
                            'error' => 'File upload failed'
                        ];
                        continue;
                    }

                    // Generate unique filename
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                    $uniqueName = Str::slug($fileName) . '_' . time() . '_' . Str::random(5) . '.' . $extension;

                    // Define storage path
                    $storagePath = 'products/media/' . date('Y/m');
                    $fullPath = $storagePath . '/' . $uniqueName;

                    // Store file
                    Storage::disk('public')->putFileAs($storagePath, $file, $uniqueName);

                    // Create thumbnails for images
                    $thumbnails = [];
                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $thumbnails = $this->createThumbnails($file, $storagePath, $uniqueName);
                    }

                    // Get file info
                    $mimeType = $file->getMimeType();
                    $fileSize = $file->getSize();

                    // Determine file type
                    $fileType = 'image';
                    if (str_starts_with($mimeType, 'image/')) {
                        $fileType = 'image';
                    }

                    // Create media record
                    $media = Media::create([
                        'file_name' => $originalName,
                        'file_path' => $fullPath,
                        'disk' => 'public',
                        'mime_type' => $mimeType,
                        'file_type' => $fileType,
                        'file_size' => $fileSize,
                        'thumbnails' => $thumbnails ?: null,
                        'metadata' => [
                            'original_name' => $originalName,
                            'extension' => $extension,
                            'dimensions' => $this->getImageDimensions($file),
                        ],
                        'alt_text' => $request->input('alt_text', pathinfo($originalName, PATHINFO_FILENAME)),
                        'uploaded_by' => auth('admin_api')->id(),
                        'uploader_type' => 'admin',
                    ]);

                    $uploadedFiles[] = [
                        'id' => $media->id,
                        'name' => $originalName,
                        'url' => asset(Storage::url($fullPath)),
                        'size' => $this->formatBytes($fileSize),
                    ];

                } catch (\Exception $e) {
                    \Log::error('File upload error: ' . $e->getMessage());
                    $failedFiles[] = [
                        'name' => $file->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ];
                }
            }

            $responseData = [
                'uploaded' => $uploadedFiles,
                'failed' => $failedFiles,
                'total_uploaded' => count($uploadedFiles),
                'total_failed' => count($failedFiles),
            ];

            $message = count($uploadedFiles) > 0 ?
                'Files uploaded successfully' :
                'No files were uploaded';

            if (count($failedFiles) > 0) {
                $message .= ' (' . count($failedFiles) . ' failed)';
            }

            return $this->apiResponse(true, $responseData, $message);

        } catch (\Exception $e) {
            \Log::error('Media upload error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to upload files', 500);
        }
    }

    /**
     * Update media metadata.
     */
    public function update(MediaRequest $request, $id): JsonResponse
    {
        try {
            $media = Media::find($id);

            if (!$media) {
                return $this->apiResponse(false, null, 'Media not found', 404);
            }

            // Update only alt text for now
            $media->update([
                'alt_text' => $request->input('alt_text'),
            ]);

            return $this->apiResponse(true, [
                'id' => $media->id,
                'alt_text' => $media->alt_text,
                'updated_at' => $media->updated_at,
            ], 'Media updated successfully');

        } catch (\Exception $e) {
            \Log::error('Media update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update media', 500);
        }
    }

    /**
     * Delete media file.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $media = Media::find($id);

            if (!$media) {
                return $this->apiResponse(false, null, 'Media not found', 404);
            }

            // Check if media is used elsewhere
            $isUsed = $this->checkMediaUsage($media);
            if ($isUsed) {
                return $this->apiResponse(false, null, 'Cannot delete media. It is being used in the system.', 400);
            }

            // Delete file from storage
            Storage::disk('public')->delete($media->file_path);

            // Delete thumbnails
            if ($media->thumbnails) {
                foreach ($media->thumbnails as $thumbnail) {
                    Storage::disk('public')->delete($thumbnail);
                }
            }

            // Delete record
            $media->delete();

            return $this->apiResponse(true, null, 'Media deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Media delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete media', 500);
        }
    }

    /**
     * Bulk delete media files.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:media,id',
            ]);

            $ids = $request->input('ids');
            $deletedCount = 0;
            $failedIds = [];

            foreach ($ids as $id) {
                try {
                    $media = Media::find($id);

                    if (!$media) {
                        $failedIds[] = ['id' => $id, 'error' => 'Media not found'];
                        continue;
                    }

                    // Check if media is used
                    $isUsed = $this->checkMediaUsage($media);
                    if ($isUsed) {
                        $failedIds[] = ['id' => $id, 'error' => 'Media is being used'];
                        continue;
                    }

                    // Delete file
                    Storage::disk('public')->delete($media->file_path);

                    // Delete thumbnails
                    if ($media->thumbnails) {
                        foreach ($media->thumbnails as $thumbnail) {
                            Storage::disk('public')->delete($thumbnail);
                        }
                    }

                    // Delete record
                    $media->delete();
                    $deletedCount++;

                } catch (\Exception $e) {
                    $failedIds[] = ['id' => $id, 'error' => $e->getMessage()];
                }
            }

            $responseData = [
                'deleted_count' => $deletedCount,
                'failed_count' => count($failedIds),
                'failed_items' => $failedIds,
            ];

            $message = $deletedCount > 0 ?
                "{$deletedCount} media file(s) deleted successfully" :
                'No media files were deleted';

            if (count($failedIds) > 0) {
                $message .= ' (' . count($failedIds) . ' failed)';
            }

            return $this->apiResponse(true, $responseData, $message);

        } catch (\Exception $e) {
            \Log::error('Bulk delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete media files', 500);
        }
    }

    /**
     * Get media statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $total = Media::count();
            $today = Media::whereDate('created_at', today())->count();
            $thisMonth = Media::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $totalSize = Media::sum('file_size');
            $avgSize = $total > 0 ? $totalSize / $total : 0;

            $byType = Media::selectRaw('file_type, COUNT(*) as count')
                ->groupBy('file_type')
                ->get()
                ->pluck('count', 'file_type');

            $latest = Media::orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->file_name,
                        'url' => asset(Storage::url($item->file_path)),
                        'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                    ];
                });

            return $this->apiResponse(true, [
                'total_files' => $total,
                'today_uploads' => $today,
                'this_month_uploads' => $thisMonth,
                'total_size' => $this->formatBytes($totalSize),
                'total_size_bytes' => $totalSize,
                'average_size' => $this->formatBytes($avgSize),
                'files_by_type' => $byType,
                'latest_uploads' => $latest,
            ], 'Statistics retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Statistics error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve statistics', 500);
        }
    }

    /**
     * Helper: Create thumbnails for image.
     */
    private function createThumbnails($file, $storagePath, $fileName): array
    {
        $thumbnails = [];
        $originalName = pathinfo($fileName, PATHINFO_FILENAME);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        try {
            $image = $this->imageManager->read($file->getRealPath());

            // Small thumbnail (150x150)
            $smallName = $originalName . '_small.' . $extension;
            $smallPath = $storagePath . '/' . $smallName;

            $smallImage = clone $image;
            $smallImage->cover(150, 150);
            Storage::disk('public')->put(
                $smallPath,
                (string) $smallImage->encodeByExtension($extension, quality: 80)
            );
            $thumbnails['small'] = $smallPath;

            // Medium thumbnail (300x300)
            $mediumName = $originalName . '_medium.' . $extension;
            $mediumPath = $storagePath . '/' . $mediumName;

            $mediumImage = clone $image;
            $mediumImage->resize(300, 300);
            Storage::disk('public')->put(
                $mediumPath,
                (string) $mediumImage->encodeByExtension($extension, quality: 85)
            );
            $thumbnails['medium'] = $mediumPath;

        } catch (\Exception $e) {
            \Log::error('Thumbnail creation error: ' . $e->getMessage());
        }

        return $thumbnails;
    }


    /**
     * Helper: Get image dimensions.
     */
    private function getImageDimensions($file): ?array
    {
        try {
            $image = $this->imageManager->read($file->getRealPath());

            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'ratio' => round($image->width() / $image->height(), 2),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * Helper: Check if media is being used.
     */
    private function checkMediaUsage(Media $media): bool
    {
        // Check all relationships
        if ($media->categories()->exists())
            return true;
        if ($media->brands()->exists())
            return true;
        if ($media->attributeValues()->exists())
            return true;
        if ($media->variantImages()->exists())
            return true;
        if ($media->reviewImages()->exists())
            return true;

        return false;
    }

    /**
     * Helper: Format bytes to human readable.
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
