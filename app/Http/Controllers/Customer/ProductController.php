<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\ProductService;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Product listing page
     */
    public function listing(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 12);
            $page = $request->get('page', 1);

            $filters = [
                'search' => $request->get('search', ''),
                'sort_by' => $request->get('sort_by', 'newest'),
                'min_price' => $request->get('min_price'),
                'max_price' => $request->get('max_price'),
                'category_id' => $request->input('category_id'),
                'brand_id' => $request->input('brand_id'),
                'attribute' => $request->get('attribute'),
                'attribute_value' => $request->get('attribute_value'),
                'specification' => $request->get('specification'),
                'specification_value' => $request->get('specification_value'),
                'in_stock' => $request->get('in_stock'),
                'is_featured' => $request->get('is_featured'),
                'is_new' => $request->get('is_new'),
                'is_bestseller' => $request->get('is_bestseller'),
            ];

            $products = $this->productService->getProducts($filters, $perPage, $page);
            $allFilters = $this->productService->getAllFilters();

            return view('customer.products.listing', [
                'products' => $products->items(),
                'paginator' => [
                    'current_page' => $products->currentPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'last_page' => $products->lastPage(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ],
                'filters' => $allFilters,
                'sortBy' => $filters['sort_by'],
                'search' => $filters['search'],
                'minPrice' => $filters['min_price'],
                'maxPrice' => $filters['max_price'],
                'categoryId' => $filters['category_id'],
                'brandId' => $filters['brand_id'],
                'inStock' => $filters['in_stock'],
                'isFeatured' => $filters['is_featured'],
                'isNew' => $filters['is_new'],
                'isBestseller' => $filters['is_bestseller'],
                'title' => 'All Products - ' . config('constants.SITE_NAME'),
            ]);

        } catch (\Exception $e) {
            Log::error('Product listing error: ' . $e->getMessage());
            return view('customer.products.listing', [
                'products' => [],
                'paginator' => [],
                'filters' => $this->productService->getAllFilters(),
                'error' => 'Failed to load products. Please try again.',
                'title' => 'Products - Error',
            ]);
        }
    }

    /**
     * Category products page
     */
    public function category($slug, Request $request)
    {
        try {
            $category = $this->productService->getCategoryBySlug($slug);

            if (!$category) {
                return redirect()->route('customer.products.list')
                    ->with('error', 'Category not found.');
            }

            $perPage = $request->get('per_page', 12);
            $page = $request->get('page', 1);

            $filters = [
                'category_id' => $this->productService->getAllCategoryIds($category->id),
                'sort_by' => $request->get('sort_by', 'newest'),
                'min_price' => $request->get('min_price'),
                'max_price' => $request->get('max_price'),
                'brand_id' => $request->get('brand_id'),
                'attribute' => $request->get('attribute'),
                'attribute_value' => $request->get('attribute_value'),
                'specification' => $request->get('specification'),
                'specification_value' => $request->get('specification_value'),
                'in_stock' => $request->get('in_stock'),
            ];

            $products = $this->productService->getProducts($filters, $perPage, $page);
            $categoryFilters = $this->productService->getCategoryFilters($category->id);
            $childCategories = $this->productService->getChildCategories($category->id);
            $relatedCategories = $this->productService->getRelatedCategories($category->id);

            return view('customer.products.category', [
                'category' => $category,
                'products' => $products->items(),
                'paginator' => [
                    'current_page' => $products->currentPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'last_page' => $products->lastPage(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ],
                'filters' => $categoryFilters,
                'childCategories' => $childCategories,
                'relatedCategories' => $relatedCategories,
                'sortBy' => $filters['sort_by'],
                'minPrice' => $filters['min_price'],
                'maxPrice' => $filters['max_price'],
                'brandId' => $filters['brand_id'],
                'inStock' => $filters['in_stock'],
                'title' => $category->name . ' - ' . config('constants.SITE_NAME'),
                'meta_description' => $category->description,
            ]);

        } catch (\Throwable $e) {
            Log::error('Category page error: ' . $e->getMessage());
            return redirect()->route('customer.products.list')
                ->with('error', 'Failed to load category. Please try again.');
        }
    }

    /**
     * Product details page
     */
    public function details($slug)
    {
        try {
            $product = $this->productService->getProductBySlug($slug);

            if (!$product) {
                return redirect()->route('customer.products.list')
                    ->with('error', 'Product not found.');
            }

            $relatedProducts = $this->productService->getRelatedProducts($product['id'], 4);
            
            // Fetch reviews
            $reviews = \App\Models\Review::where('product_id', $product['id'])
                        ->where('status', true)
                        ->latest()
                        ->get();

            // Calculate rating breakdown
            $ratingBreakdown = [
                5 => ['count' => 0, 'percent' => 0],
                4 => ['count' => 0, 'percent' => 0],
                3 => ['count' => 0, 'percent' => 0],
                2 => ['count' => 0, 'percent' => 0],
                1 => ['count' => 0, 'percent' => 0],
            ];

            $totalReviews = $reviews->count();
            $averageRating = 0;

            if ($totalReviews > 0) {
                $totalPoints = 0;
                foreach ($reviews as $review) {
                    $rating = (int) $review->rating;
                    $totalPoints += $rating;
                    if (isset($ratingBreakdown[$rating])) {
                        $ratingBreakdown[$rating]['count']++;
                    }
                }

                foreach ($ratingBreakdown as $star => &$data) {
                    $data['percent'] = round(($data['count'] / $totalReviews) * 100);
                }

                $averageRating = $totalPoints / $totalReviews;
            } else {
                // If no reviews, use a default placeholder or 0
                $averageRating = (float)($product['rating'] ?? 0);
                $totalReviews = (int)($product['review_count'] ?? 0);
            }

            // Sync product stats with actual reviews
            $product['rating'] = $averageRating;
            $product['review_count'] = $totalReviews;

            return view('customer.products.details', [
                'product' => $product,
                'relatedProducts' => $relatedProducts,
                'reviews' => $reviews,
                'ratingBreakdown' => $ratingBreakdown,
                'title' => $product['name'] . ' - ' . config('constants.SITE_NAME'),
                'meta_title' => $product['meta_title'] ?? $product['name'],
                'meta_description' => $product['meta_description'] ?? $product['short_description'],
                'meta_keywords' => $product['meta_keywords'] ?? null,
            ]);

        } catch (\Exception $e) {
            Log::error('Product details error: ' . $e->getMessage());
            return redirect()->route('customer.products.list')
                ->with('error', 'Product not found.');
        }
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        try {
            $searchQuery = $request->get('q', '');

            if (empty($searchQuery)) {
                return redirect()->route('customer.products.list');
            }

            $perPage = $request->get('per_page', 12);
            $page = $request->get('page', 1);

            $filters = [
                'sort_by' => $request->get('sort_by', 'newest'),
                'min_price' => $request->get('min_price'),
                'max_price' => $request->get('max_price'),
                'category_id' => $request->get('category_id'),
                'brand_id' => $request->get('brand_id'),
                'in_stock' => $request->get('in_stock'),
                'is_featured' => $request->get('is_featured'),
                'is_new' => $request->get('is_new'),
                'is_bestseller' => $request->get('is_bestseller'),
            ];

            $products = $this->productService->searchProducts($searchQuery, $filters, $perPage, $page);
            $allFilters = $this->productService->getAllFilters();

            return view('customer.products.search', [
                'searchQuery' => $searchQuery,
                'products' => $products->items(),
                'paginator' => [
                    'current_page' => $products->currentPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'last_page' => $products->lastPage(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ],
                'filters' => $allFilters,
                'sortBy' => $filters['sort_by'],
                'minPrice' => $filters['min_price'],
                'maxPrice' => $filters['max_price'],
                'categoryId' => $filters['category_id'],
                'brandId' => $filters['brand_id'],
                'inStock' => $filters['in_stock'],
                'title' => 'Search: ' . $searchQuery . ' - ' . config('constants.SITE_NAME'),
                'meta_description' => 'Search results for ' . $searchQuery . ' in ' . config('constants.SITE_NAME'),
            ]);

        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());
            return view('customer.products.search', [
                'searchQuery' => $request->get('q', ''),
                'products' => [],
                'paginator' => [],
                'filters' => $this->productService->getAllFilters(),
                'error' => 'Search failed. Please try again.',
                'title' => 'Search Error',
            ]);
        }
    }

    /**
     * Quick view product
     */
    public function quickView($slug)
    {
        try {
            $product = $this->productService->getProductBySlug($slug);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $product,
                'html' => view('customer.products.partials.quick-view', ['product' => $product])->render()
            ]);

        } catch (\Exception $e) {
            Log::error('Quick view error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }

/**
 * Rugs category page
 */
public function rugs(Request $request)
{
    try {
        // First, check if 'rugs' exists as a category
        $category = $this->productService->getCategoryBySlug('rugs');
        
        // If rugs category doesn't exist, you could:
        // 1. Create a custom rugs listing with specific filters
        // 2. Redirect to general products listing with rugs as a search term
        // 3. Show a custom rugs page
        
        // Option 1: If you want to use it as a special category page (recommended)
        if ($category) {
            return $this->category('rugs', $request);
        }
        
        // Option 2: If rugs is not a category, show products with "rug" in name/description
        $perPage = $request->get('per_page', 12);
        $page = $request->get('page', 1);

        $filters = [
            'search' => 'rug', // Search for products with "rug" in them
            'sort_by' => $request->get('sort_by', 'newest'),
            'min_price' => $request->get('min_price'),
            'max_price' => $request->get('max_price'),
            'category_id' => $request->get('category_id'),
            'brand_id' => $request->get('brand_id'),
            'attribute' => $request->get('attribute'),
            'attribute_value' => $request->get('attribute_value'),
            'specification' => $request->get('specification'),
            'specification_value' => $request->get('specification_value'),
            'in_stock' => $request->get('in_stock'),
            'is_featured' => $request->get('is_featured'),
            'is_new' => $request->get('is_new'),
            'is_bestseller' => $request->get('is_bestseller'),
        ];

        $products = $this->productService->getProducts($filters, $perPage, $page);
        $allFilters = $this->productService->getAllFilters();
        
        // Get rugs-specific filters if needed
        $rugsFilters = $this->productService->getCategoryFilters(null, 'rug');

        return view('customer.products.rugs', [
            'products' => $products->items(),
            'paginator' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
            'filters' => $allFilters,
            'rugsFilters' => $rugsFilters,
            'sortBy' => $filters['sort_by'],
            'search' => $filters['search'],
            'minPrice' => $filters['min_price'] !== null ? (int)$filters['min_price'] : null,
            'maxPrice' => $filters['max_price'] !== null ? (int)$filters['max_price'] : null,
            'categoryId' => $filters['category_id'],
            'brandId' => $filters['brand_id'],
            'inStock' => $filters['in_stock'],
            'isFeatured' => $filters['is_featured'],
            'isNew' => $filters['is_new'],
            'isBestseller' => $filters['is_bestseller'],
            'og_title' => 'Rugs Collection - ' . config('constants.SITE_NAME'),
            'og_description' => 'Explore our premium collection of rugs. Find stylish, durable rugs for every room in your home.',
            'title' => 'Rugs Collection - ' . config('constants.SITE_NAME'),
            'meta_description' => 'Explore our premium collection of rugs. Find stylish, durable rugs for every room in your home.',
            'meta_keywords' => 'rugs, carpets, home decor, floor rugs, living room rugs, bedroom rugs',
        ]);

        } catch (\Exception $e) {
            Log::error('Rugs page error: ' . $e->getMessage());
            return redirect()->route('customer.products.list')
                ->with('error', 'Failed to load rugs collection. Please try again.');
        }
    }

    /**
     * Store a product review
     */
    public function storeReview(Request $request, $slug)
    {
        try {
            $product = $this->productService->getProductBySlug($slug);

            if (!$product) {
                return back()->with('error', 'Product not found.');
            }

            $request->validate([
                'user_name' => 'required|string|max:255',
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'required|string|max:1000',
            ]);

            \App\Models\Review::create([
                'product_id' => $product['id'],
                'user_name' => $request->user_name,
                'rating' => $request->rating,
                'review' => $request->review,
                'status' => true, // Auto-approve for now
            ]);

            return back()->with('success', 'Review submitted successfully!');

        } catch (\Exception $e) {
            Log::error('Review storage error: ' . $e->getMessage());
            return back()->with('error', 'Failed to submit review. Please try again.');
        }
    }
}
