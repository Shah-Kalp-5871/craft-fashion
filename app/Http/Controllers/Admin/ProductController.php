<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Services\Admin\ProductService;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\TaxClass;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $query = Product::with(['mainCategory', 'brand', 'defaultVariant.images', 'taxClass']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhereHas('defaultVariant', function($v) use ($search) {
                      $v->where('sku', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('product_type', $request->type);
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        $brands = Brand::where('status', 1)->get();
        $taxClasses = TaxClass::all();
        $tags = Tag::all();

        return view('admin.products.create', compact('categories', 'brands', 'taxClasses', 'tags'));
    }

    public function store(ProductRequest $request)
    {
        $result = $this->productService->createProduct($request->validated());

        if ($result['success']) {
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        }

        return back()->withInput()->with('error', $result['error']);
    }

    public function edit($id)
    {
        // Eager load everything needed for the view, mirroring Service logic but as Eloquent model
        $product = Product::with([
            'tags', 
            'categories', 
            'defaultVariant.images', // For simple product data
            'brand',
            'mainCategory',
            'variants.images',
            'variants.primaryImage.media',
            'specifications' => function($q) {
                $q->with('values'); 
            }
        ])->findOrFail($id);
        
        $categories = Category::with('children')->whereNull('parent_id')->get();
        $brands = Brand::where('status', 1)->get();
        $taxClasses = TaxClass::all();
        $tags = Tag::all();
        
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'taxClasses', 'tags'));
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $result = $this->productService->updateProduct($product, $request->validated());

        if ($result['success']) {
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        }

        return back()->withInput()->with('error', $result['error']);
    }

    public function destroy(Product $product)
    {
        if ($product->orderItems()->exists()) {
             return back()->with('error', 'Cannot delete product. It has associated orders.');
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    // AJAX Endpoints used by Blade Views (axios)
    
    public function getCategorySpecifications($categoryId)
    {
        try {
            $specs = $this->productService->getCategorySpecifications($categoryId);
            return response()->json([
                'success' => true,
                'data' => $specs
            ]);
        } catch (\Exception $e) {
             return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getCategoryAttributes($categoryId)
    {
        try {
            $attrs = $this->productService->getCategoryAttributes($categoryId);
            return response()->json([
                'success' => true,
                'data' => $attrs
            ]);
        } catch (\Exception $e) {
             return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function attributes()
    {
        return view('admin.products.attributes');
    }

    public function specifications()
    {
        return view('admin.products.specifications');
    }

    public function tags()
    {
        return view('admin.products.tags');
    }

    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%");
        }

        $products = $query->latest()->limit(20)->get();

        return response()->json([
            'success' => true,
            'data' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => asset('storage/' . $product->main_image),
                ];
            })
        ]);
    }
}
