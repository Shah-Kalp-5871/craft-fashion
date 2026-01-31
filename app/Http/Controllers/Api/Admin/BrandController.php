<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\BrandRequest;
use App\Models\Brand;
use App\Models\Media;
use App\Models\SeoMetadata;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
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
     * Display a listing of brands.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Get query parameters
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            $search = $request->get('search', '');
            $sortBy = $request->get('sort', 'created_at');
            $sortDir = $request->get('direction', 'desc');
            $status = $request->get('status');
            $featured = $request->get('featured');

            // Build query
            $query = Brand::with(['logo'])->withCount('products');

            // Apply filters
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('slug', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                });
            }

            if ($status) {
                $query->where('status', $status === 'active' ? 1 : 0);
            }

            if ($featured !== null) {
                $query->where('featured', (bool) $featured);
            }

            // Apply sorting
            $query->orderBy($sortBy, $sortDir);

            // Paginate results
            $brands = $query->paginate($perPage, ['*'], 'page', $page);

            // Transform data for frontend
            $transformedData = $brands->getCollection()->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'slug' => $brand->slug,
                    'description' => $brand->description,
                    'logo' => $brand->logo ? asset(Storage::url($brand->logo->file_path)) : null,
                    'logo_id' => $brand->logo_id,
                    'status' => $brand->status ? 'active' : 'inactive',
                    'featured' => $brand->featured,
                    'sort_order' => $brand->sort_order,
                    'website' => $brand->website,
                    'email' => $brand->email,
                    'phone' => $brand->phone,
                    'country' => $brand->country,
                    'address' => $brand->address,
                    'meta_title' => $brand->meta_title,
                    'meta_description' => $brand->meta_description,
                    'meta_keywords' => $brand->meta_keywords,
                    'product_count' => $brand->products_count,
                    'created_at' => $brand->created_at,
                    'created_at_formatted' => $brand->created_at->format('M d, Y'),
                    'updated_at' => $brand->updated_at,
                ];
            })->values()->all();

            return $this->apiResponse(true, [
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $brands->currentPage(),
                    'from' => $brands->firstItem(),
                    'to' => $brands->lastItem(),
                    'per_page' => $brands->perPage(),
                    'total' => $brands->total(),
                    'last_page' => $brands->lastPage(),
                ],
                'links' => [
                    'first' => $brands->url(1),
                    'last' => $brands->url($brands->lastPage()),
                    'prev' => $brands->previousPageUrl(),
                    'next' => $brands->nextPageUrl(),
                ],
            ], 'Brands list retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Brand index error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve brands list', 500);
        }
    }

    /**
     * Store a newly created brand.
     */
    public function store(BrandRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // Handle logo upload if present
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');

                // Generate unique filename
                $originalName = $logoFile->getClientOriginalName();
                $extension = $logoFile->getClientOriginalExtension();
                $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                $uniqueName = 'brand_' . Str::slug($fileName) . '_' . time() . '.' . $extension;

                // Define storage path
                $storagePath = 'brands/logos/' . date('Y/m');
                $fullPath = $storagePath . '/' . $uniqueName;

                // Store file
                Storage::disk('public')->putFileAs($storagePath, $logoFile, $uniqueName);

                // Create media record
                $media = Media::create([
                    'file_name' => $originalName,
                    'file_path' => $fullPath,
                    'disk' => 'public',
                    'mime_type' => $logoFile->getMimeType(),
                    'file_type' => 'image',
                    'file_size' => $logoFile->getSize(),
                    'alt_text' => $data['name'] . ' logo',
                    'uploaded_by' => auth()->id(),
                    'uploader_type' => 'admin',
                ]);

                $data['logo_id'] = $media->id;
                unset($data['logo']);
            }

            // Convert status to boolean
            $data['status'] = $data['status'] === 'active' ? 1 : 0;

            // Create brand
            $brand = Brand::create($data);

            // Create SEO metadata if provided
            if (!empty($data['meta_title']) || !empty($data['meta_description']) || !empty($data['meta_keywords'])) {
                SeoMetadata::create([
                    'entity_type' => Brand::class,
                    'entity_id' => $brand->id,
                    'meta_title' => $data['meta_title'] ?? null,
                    'meta_description' => $data['meta_description'] ?? null,
                    'meta_keywords' => $data['meta_keywords'] ?? null,
                ]);
            }

            // Load relationships for response
            $brand->load(['logo']);

            return $this->apiResponse(true, [
                'id' => $brand->id,
                'name' => $brand->name,
                'slug' => $brand->slug,
                'logo' => $brand->logo ? asset(Storage::url($brand->logo->file_path)) : null,
                'status' => $brand->status ? 'active' : 'inactive',
                'featured' => $brand->featured,
            ], 'Brand created successfully', 201);

        } catch (\Exception $e) {
            \Log::error('Brand store error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to create brand', 500);
        }
    }

    /**
     * Display the specified brand.
     */
    public function show($id): JsonResponse
    {
        try {
            $brand = Brand::with(['logo', 'seoMetadata'])->withCount('products')->find($id);

            if (!$brand) {
                return $this->apiResponse(false, null, 'Brand not found', 404);
            }

            return $this->apiResponse(true, [
                'id' => $brand->id,
                'name' => $brand->name,
                'slug' => $brand->slug,
                'description' => $brand->description,
                'logo' => $brand->logo ? asset(Storage::url($brand->logo->file_path)) : null,
                'logo_id' => $brand->logo_id,
                'status' => $brand->status ? 'active' : 'inactive',
                'featured' => $brand->featured,
                'sort_order' => $brand->sort_order,
                'website' => $brand->website,
                'email' => $brand->email,
                'phone' => $brand->phone,
                'country' => $brand->country,
                'address' => $brand->address,
                'meta_title' => $brand->meta_title,
                'meta_description' => $brand->meta_description,
                'meta_keywords' => $brand->meta_keywords,
                'product_count' => $brand->products_count,
                'created_at' => $brand->created_at,
                'updated_at' => $brand->updated_at,
                'seo_metadata' => $brand->seoMetadata,
            ], 'Brand retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Brand show error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve brand', 500);
        }
    }

    /**
     * Update the specified brand.
     */
    public function update(BrandRequest $request, $id): JsonResponse
    {
        try {
            $brand = Brand::find($id);

            if (!$brand) {
                return $this->apiResponse(false, null, 'Brand not found', 404);
            }

            $data = $request->validated();

            // Handle logo upload if present
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');

                // Delete old logo if exists
                if ($brand->logo) {
                    Storage::disk('public')->delete($brand->logo->file_path);
                    $brand->logo->delete();
                }

                // Generate unique filename
                $originalName = $logoFile->getClientOriginalName();
                $extension = $logoFile->getClientOriginalExtension();
                $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                $uniqueName = 'brand_' . Str::slug($fileName) . '_' . time() . '.' . $extension;

                // Define storage path
                $storagePath = 'brands/logos/' . date('Y/m');
                $fullPath = $storagePath . '/' . $uniqueName;

                // Store file
                Storage::disk('public')->putFileAs($storagePath, $logoFile, $uniqueName);

                // Create media record
                $media = Media::create([
                    'file_name' => $originalName,
                    'file_path' => $fullPath,
                    'disk' => 'public',
                    'mime_type' => $logoFile->getMimeType(),
                    'file_type' => 'image',
                    'file_size' => $logoFile->getSize(),
                    'alt_text' => $data['name'] . ' logo',
                    'uploaded_by' => auth()->id(),
                    'uploader_type' => 'admin',
                ]);

                $data['logo_id'] = $media->id;
                unset($data['logo']);
            } else {
                // If logo is being removed
                if ($request->has('remove_logo') && $request->remove_logo) {
                    if ($brand->logo) {
                        Storage::disk('public')->delete($brand->logo->file_path);
                        $brand->logo->delete();
                        $data['logo_id'] = null;
                    }
                }
            }

            if (array_key_exists('status', $data)) {
                $data['status'] = $data['status'] === 'active' ? 1 : 0;
            }


            // Update brand
            $brand->update($data);

            // Update or create SEO metadata
            if (!empty($data['meta_title']) || !empty($data['meta_description']) || !empty($data['meta_keywords'])) {
                SeoMetadata::updateOrCreate(
                    [
                        'entity_type' => Brand::class,
                        'entity_id' => $brand->id,
                    ],
                    [
                        'meta_title' => $data['meta_title'] ?? null,
                        'meta_description' => $data['meta_description'] ?? null,
                        'meta_keywords' => $data['meta_keywords'] ?? null,
                    ]
                );
            }

            // Load relationships for response
            $brand->load(['logo']);

            return $this->apiResponse(true, [
                'id' => $brand->id,
                'name' => $brand->name,
                'slug' => $brand->slug,
                'logo' => $brand->logo ? asset(Storage::url($brand->logo->file_path)) : null,
                'status' => $brand->status ? 'active' : 'inactive',
                'featured' => $brand->featured,
            ], 'Brand updated successfully');

        } catch (\Exception $e) {
            \Log::error('Brand update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update brand', 500);
        }
    }

    /**
     * Remove the specified brand.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $brand = Brand::find($id);

            if (!$brand) {
                return $this->apiResponse(false, null, 'Brand not found', 404);
            }

            // Check if brand has products
            if ($brand->products()->exists()) {
                return $this->apiResponse(false, null, 'Cannot delete brand. It has associated products. Please reassign products first.', 400);
            }

            // Delete logo if exists
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo->file_path);
                $brand->logo->delete();
            }

            // Delete SEO metadata
            $brand->seoMetadata()->delete();

            // Delete brand
            $brand->delete();

            return $this->apiResponse(true, null, 'Brand deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Brand delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete brand', 500);
        }
    }

    /**
     * Get brand statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $total = Brand::count();
            $active = Brand::where('status', 1)->count();
            $inactive = Brand::where('status', 0)->count();
            $featured = Brand::where('featured', true)->count();

            // Get brand with most products
            $popularBrand = Brand::withCount('products')
                ->orderBy('products_count', 'desc')
                ->first();

            // Count brands with/without logo
            $withLogo = Brand::whereNotNull('logo_id')->count();
            $withoutLogo = $total - $withLogo;

            // Recent brands (last 7 days)
            $recent = Brand::where('created_at', '>=', now()->subDays(7))
                ->count();

            return $this->apiResponse(true, [
                'total_brands' => $total,
                'active_brands' => $active,
                'inactive_brands' => $inactive,
                'featured_brands' => $featured,
                'popular_brand' => $popularBrand ? [
                    'id' => $popularBrand->id,
                    'name' => $popularBrand->name,
                    'product_count' => $popularBrand->products_count,
                ] : null,
                'brands_with_logo' => $withLogo,
                'brands_without_logo' => $withoutLogo,
                'recent_brands' => $recent,
                'percentage_active' => $total > 0 ? round(($active / $total) * 100, 2) : 0,
            ], 'Statistics retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Brand statistics error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve statistics', 500);
        }
    }

    /**
     * Update brand status.
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:active,inactive',
            ]);

            $brand = Brand::find($id);

            if (!$brand) {
                return $this->apiResponse(false, null, 'Brand not found', 404);
            }

            $brand->update([
                'status' => $request->status === 'active' ? 1 : 0,
            ]);

            return $this->apiResponse(true, [
                'id' => $brand->id,
                'status' => $brand->status ? 'active' : 'inactive',
            ], 'Brand status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Brand status update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update status', 500);
        }
    }

    /**
     * Update brand featured status.
     */
    public function updateFeatured(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'featured' => 'required|boolean',
            ]);

            $brand = Brand::find($id);

            if (!$brand) {
                return $this->apiResponse(false, null, 'Brand not found', 404);
            }

            $brand->update([
                'featured' => $request->featured,
            ]);

            return $this->apiResponse(true, [
                'id' => $brand->id,
                'featured' => $brand->featured,
            ], 'Brand featured status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Brand featured update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update featured status', 500);
        }
    }

    /**
     * Bulk update status.
     */
    public function bulkStatus(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:brands,id',
                'status' => 'required|in:active,inactive',
            ]);

            $updated = Brand::whereIn('id', $request->ids)
                ->update([
                    'status' => $request->status === 'active' ? 1 : 0,
                ]);

            return $this->apiResponse(true, [
                'updated_count' => $updated,
            ], "{$updated} brand(s) status updated successfully");

        } catch (\Exception $e) {
            \Log::error('Bulk status update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update status', 500);
        }
    }

    /**
     * Bulk update featured status.
     */
    public function bulkFeatured(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:brands,id',
                'featured' => 'required|boolean',
            ]);

            $updated = Brand::whereIn('id', $request->ids)
                ->update([
                    'featured' => $request->featured,
                ]);

            return $this->apiResponse(true, [
                'updated_count' => $updated,
            ], "{$updated} brand(s) featured status updated successfully");

        } catch (\Exception $e) {
            \Log::error('Bulk featured update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update featured status', 500);
        }
    }

    /**
     * Bulk delete brands.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:brands,id',
            ]);

            // Check if any brand has products
            $brandsWithProducts = Brand::whereIn('id', $request->ids)
                ->has('products')
                ->count();

            if ($brandsWithProducts > 0) {
                return $this->apiResponse(false, null, "Cannot delete {$brandsWithProducts} brand(s) that have associated products", 400);
            }

            // Get brands with logos to delete files
            $brands = Brand::with('logo')->whereIn('id', $request->ids)->get();

            // Delete logos
            foreach ($brands as $brand) {
                if ($brand->logo) {
                    Storage::disk('public')->delete($brand->logo->file_path);
                    $brand->logo->delete();
                }
                $brand->seoMetadata()->delete();
            }

            // Delete brands
            $deleted = Brand::whereIn('id', $request->ids)->delete();

            return $this->apiResponse(true, [
                'deleted_count' => $deleted,
            ], "{$deleted} brand(s) deleted successfully");

        } catch (\Exception $e) {
            \Log::error('Bulk delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete brands', 500);
        }
    }


    /**
     * Get brands for dropdown.
     */
    public function dropdown(Request $request): JsonResponse
    {
        try {
            $excludeId = $request->get('exclude_id');
            $onlyFeatured = $request->get('featured');

            $query = Brand::select('id', 'name', 'slug')
                ->where('status', 1)
                ->orderBy('sort_order')
                ->orderBy('name');

            // Exclude a specific brand (useful while editing)
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            // Only featured brands (optional)
            if ($onlyFeatured !== null) {
                $query->where('featured', (bool) $onlyFeatured);
            }

            $brands = $query->get();

            return $this->apiResponse(true, $brands, 'Brands retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Brand dropdown error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve brands', 500);
        }
    }

}
