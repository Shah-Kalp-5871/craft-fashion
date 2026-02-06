<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeSectionController extends Controller
{
    public function index()
    {
        $sections = HomeSection::with('category')->orderBy('sort_order')->get();
        return view('admin.crm.home-sections.index', compact('sections'));
    }

    public function create()
    {
        $categories = Category::all();
        $styles = [
            'style_1' => 'Style 1 (3 Column Equal Grid)',
            'style_2' => 'Style 2 (Creative Masonry)',
            'style_3' => 'Style 3 (Horizontal Scroll)',
            'style_4' => 'Style 4 (Center Featured + Side Items)',
            'style_5' => 'Style 5 (Diagonal Staggered)',
            'style_6' => 'Style 6 (Circular/Hexagon Layout)',
            'style_7' => 'Style 7 (2 Column Featured Grid)',
            'style_discover' => 'Discover Your Style (Category Grid)',
        ];
        return view('admin.crm.home-sections.create', compact('categories', 'styles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'type' => 'required|in:category,custom_products',
            'category_id' => 'required_if:type,category|nullable|exists:categories,id',
            'product_ids' => 'required_if:type,custom_products|nullable|array',
            'style' => 'required|string',
            'sort_order' => 'integer',
        ]);

        HomeSection::create($request->all());

        return redirect()->route('admin.home-sections.index')->with('success', 'Section created successfully.');
    }

    public function edit(HomeSection $section)
    {
        $categories = Category::all();
        $styles = [
            'style_1' => 'Style 1 (3 Column Equal Grid)',
            'style_2' => 'Style 2 (Creative Masonry)',
            'style_3' => 'Style 3 (Horizontal Scroll)',
            'style_4' => 'Style 4 (Center Featured + Side Items)',
            'style_5' => 'Style 5 (Diagonal Staggered)',
            'style_6' => 'Style 6 (Circular/Hexagon Layout)',
            'style_7' => 'Style 7 (2 Column Featured Grid)',
            'style_discover' => 'Discover Your Style (Category Grid)',
        ];
        
        $selectedProducts = [];
        if ($section->type === 'custom_products' && !empty($section->product_ids)) {
            $selectedProducts = Product::whereIn('id', $section->product_ids)->get();
        }

        return view('admin.crm.home-sections.edit', compact('section', 'categories', 'styles', 'selectedProducts'));
    }

    public function update(Request $request, HomeSection $section)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'type' => 'required|in:category,custom_products',
            'category_id' => 'required_if:type,category|nullable|exists:categories,id',
            'product_ids' => 'required_if:type,custom_products|nullable|array',
            'style' => 'required|string',
            'sort_order' => 'integer',
        ]);

        $section->update($request->all());

        return redirect()->route('admin.home-sections.index')->with('success', 'Section updated successfully.');
    }

    public function destroy(HomeSection $section)
    {
        $section->delete();
        return redirect()->route('admin.home-sections.index')->with('success', 'Section deleted successfully.');
    }

    public function toggleStatus(HomeSection $section)
    {
        $section->status = !$section->status;
        $section->save();
        return response()->json(['success' => true, 'status' => $section->status]);
    }
}
