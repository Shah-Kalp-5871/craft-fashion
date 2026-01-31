<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\TagRequest;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagController extends Controller
{
    private function apiResponse($success = true, $data = null, $message = '', $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * Display a listing of tags.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDir = $request->get('sort_dir', 'desc');
            $status = $request->get('status');
            $featured = $request->get('featured');

            $query = Tag::withCount('products');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('slug', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            if ($status !== null) {
                $query->where('status', (int) $status);
            }

            if ($featured !== null) {
                $query->where('featured', (int) $featured);
            }

            $query->orderBy($sortBy, $sortDir);
            $tags = $query->paginate($perPage);

            $transformedData = $tags->getCollection()->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'description' => $tag->description,
                    'color' => $tag->color,
                    'icon' => $tag->icon,
                    'featured' => (bool) $tag->featured,
                    'status' => (bool) $tag->status,
                    'product_count' => $tag->products_count,
                    'created_at' => $tag->created_at->format('Y-m-d H:i:s'),
                    'created_at_formatted' => $tag->created_at->format('M d, Y'),
                    'updated_at' => $tag->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return $this->apiResponse(true, [
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $tags->currentPage(),
                    'per_page' => $tags->perPage(),
                    'total' => $tags->total(),
                    'last_page' => $tags->lastPage(),
                ]
            ], 'Tags retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Tag index error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve tags', 500);
        }
    }

    /**
     * Store a newly created tag.
     */
    public function store(TagRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Set default values if not provided
            $data['featured'] = $request->has('featured') ? (int) $data['featured'] : 0;
            $data['status'] = (int) $data['status'];

            $tag = Tag::create($data);

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
                'status' => (bool) $tag->status,
                'featured' => (bool) $tag->featured,
            ], 'Tag created successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Tag store error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to create tag', 500);
        }
    }

    /**
     * Display the specified tag.
     */
    public function show($id): JsonResponse
    {
        try {
            $tag = Tag::withCount('products')->find($id);

            if (!$tag) {
                return $this->apiResponse(false, null, 'Tag not found', 404);
            }

            return $this->apiResponse(true, [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
                'description' => $tag->description,
                'color' => $tag->color,
                'icon' => $tag->icon,
                'featured' => (bool) $tag->featured,
                'status' => (bool) $tag->status,
                'product_count' => $tag->products_count,
                'created_at' => $tag->created_at,
                'updated_at' => $tag->updated_at,
            ], 'Tag retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Tag show error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve tag', 500);
        }
    }

    /**
     * Update the specified tag.
     */
    public function update(TagRequest $request, $id): JsonResponse
    {
        try {
            $tag = Tag::find($id);

            if (!$tag) {
                return $this->apiResponse(false, null, 'Tag not found', 404);
            }

            DB::beginTransaction();

            $data = $request->validated();

            $tag->update($data);

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
                'status' => (bool) $tag->status,
                'featured' => (bool) $tag->featured,
            ], 'Tag updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Tag update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update tag', 500);
        }
    }

    /**
     * Remove the specified tag.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $tag = Tag::find($id);

            if (!$tag) {
                return $this->apiResponse(false, null, 'Tag not found', 404);
            }

            // Check if tag has products
            if ($tag->products()->exists()) {
                return $this->apiResponse(false, null, 'Cannot delete tag. It has associated products.', 400);
            }

            $tag->delete();

            return $this->apiResponse(true, null, 'Tag deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Tag delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete tag', 500);
        }
    }

    /**
     * Get tag statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $total = Tag::count();
            $active = Tag::where('status', 1)->count();
            $featured = Tag::where('featured', 1)->where('status', 1)->count();

            // Get most used tag (with most products)
            $popularTag = Tag::withCount('products')
                ->where('status', 1)
                ->orderBy('products_count', 'desc')
                ->first();

            return $this->apiResponse(true, [
                'total_tags' => $total,
                'active_tags' => $active,
                'featured_tags' => $featured,
                'popular_tag' => $popularTag ? [
                    'name' => $popularTag->name,
                    'product_count' => $popularTag->products_count,
                ] : null,
            ], 'Tag statistics retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Tag statistics error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve statistics', 500);
        }
    }

    /**
     * Get popular tags (most used).
     */
    public function popular(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);

            $popularTags = Tag::withCount('products')
                ->where('status', 1)
                ->orderBy('products_count', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                        'slug' => $tag->slug,
                        'color' => $tag->color,
                        'icon' => $tag->icon,
                        'product_count' => $tag->products_count,
                    ];
                });

            return $this->apiResponse(true, $popularTags, 'Popular tags retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Popular tags error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve popular tags', 500);
        }
    }

    /**
     * Update tag status.
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:0,1,true,false'
            ]);

            $tag = Tag::find($id);

            if (!$tag) {
                return $this->apiResponse(false, null, 'Tag not found', 404);
            }

            $status = $request->status;
            if (is_bool($status) || $status === 'true' || $status === 'false') {
                $status = filter_var($status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            } else {
                $status = (int) $status;
            }

            $tag->update(['status' => $status]);

            return $this->apiResponse(true, [
                'id' => $tag->id,
                'status' => (bool) $tag->status,
            ], 'Tag status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Tag status update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update tag status', 500);
        }
    }

    /**
     * Update tag featured status.
     */
    public function updateFeatured(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'featured' => 'required|boolean'
            ]);

            $tag = Tag::find($id);

            if (!$tag) {
                return $this->apiResponse(false, null, 'Tag not found', 404);
            }

            $tag->update(['featured' => (int) $request->featured]);

            return $this->apiResponse(true, [
                'id' => $tag->id,
                'featured' => (bool) $tag->featured,
            ], 'Tag featured status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Tag featured update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update featured status', 500);
        }
    }

    /**
     * Bulk update tag status.
     */
    public function bulkStatus(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:tags,id',
                'status' => 'required|in:0,1,true,false'
            ]);

            $status = $request->status;
            if (is_bool($status) || $status === 'true' || $status === 'false') {
                $status = filter_var($status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            } else {
                $status = (int) $status;
            }

            $updated = Tag::whereIn('id', $request->ids)
                ->update(['status' => $status]);

            return $this->apiResponse(true, [
                'updated_count' => $updated,
            ], "{$updated} tag(s) status updated successfully");

        } catch (\Exception $e) {
            \Log::error('Tag bulk status error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update tags status', 500);
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
                'ids.*' => 'integer|exists:tags,id',
                'featured' => 'required|boolean'
            ]);

            $updated = Tag::whereIn('id', $request->ids)
                ->update(['featured' => (int) $request->featured]);

            return $this->apiResponse(true, [
                'updated_count' => $updated,
            ], "{$updated} tag(s) featured status updated successfully");

        } catch (\Exception $e) {
            \Log::error('Tag bulk featured error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update tags featured status', 500);
        }
    }

    /**
     * Bulk delete tags.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:tags,id',
            ]);

            // Check if any tag has products
            $tagsWithProducts = Tag::whereIn('id', $request->ids)
                ->has('products')
                ->count();

            if ($tagsWithProducts > 0) {
                return $this->apiResponse(false, null, "Cannot delete {$tagsWithProducts} tag(s) that have associated products", 400);
            }

            $deleted = Tag::whereIn('id', $request->ids)->delete();

            return $this->apiResponse(true, [
                'deleted_count' => $deleted,
            ], "{$deleted} tag(s) deleted successfully");

        } catch (\Exception $e) {
            \Log::error('Tag bulk delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete tags', 500);
        }
    }

    /**
     * Get tags for dropdown.
     */
    public function dropdown(): JsonResponse
    {
        try {
            $tags = Tag::select('id', 'name', 'slug', 'color', 'icon')
                ->where('status', 1)
                ->orderBy('name')
                ->get()
                ->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                        'slug' => $tag->slug,
                        'color' => $tag->color,
                        'icon' => $tag->icon,
                    ];
                });

            return $this->apiResponse(true, $tags, 'Tags retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Tag dropdown error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve tags', 500);
        }
    }
}
