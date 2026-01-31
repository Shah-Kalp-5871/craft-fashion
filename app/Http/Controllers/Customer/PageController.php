<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        // Map slugs to potential blade files
        $viewMap = [
            'about-us' => 'about',
            'contact-us' => 'contact',
            'faq' => 'faq',
            'terms-and-conditions' => 'terms',
            'privacy-policy' => 'privacy',
            'shipping-policy' => 'shipping-policy',
            'size-guide' => 'size-guide',
        ];

        $viewName = $viewMap[$slug] ?? $slug;

        if (view()->exists("customer.pages.{$viewName}")) {
            // Check if the file is not empty (hacky check but useful here if needed)
            // For now, if it exists, we assume it's the intended view
            return view("customer.pages.{$viewName}");
        }

        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('customer.pages.show', compact('page'));
    }

}
