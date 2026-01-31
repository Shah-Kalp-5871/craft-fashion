<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        \Illuminate\Support\Facades\View::composer('customer.partials.header', function ($view) {
            $view->with('navCategories', \App\Models\Category::where('show_in_nav', true)
                ->where('status', true)
                ->orderBy('sort_order')
                ->get());

            // Add Cart Count Logic
            $cartHelper = new \App\Helpers\CartHelper();
            $count = $cartHelper->getCartCount();
            $view->with('cartCount', $count);

            // Add Wishlist Count Logic
            $wishlistCount = 0;
            $wishlistVariantIds = [];
            if (auth()->guard('customer')->check()) {
                $wishlist = \App\Models\Wishlist::where('customer_id', auth()->guard('customer')->id())->first();
                if ($wishlist) {
                    $wishlistCount = $wishlist->items()->count();
                    $wishlistVariantIds = $wishlist->items()->pluck('product_variant_id')->toArray();
                }
            }
            $view->with('wishlistCount', $wishlistCount);
            $view->with('wishlistVariantIds', $wishlistVariantIds);
        });
    }
}
