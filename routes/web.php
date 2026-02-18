<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CategoryController as AdminCategory;
use App\Http\Controllers\Admin\BrandController as AdminBrand;
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\MediaController as AdminMedia;
use App\Http\Controllers\Admin\TaxController as AdminTax;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\NotificationController as AdminNotification;
use App\Http\Controllers\Admin\CRMController as AdminCRM;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\ShippingController as AdminShipping;
use App\Http\Controllers\Admin\SettingController as AdminSetting;
use App\Http\Controllers\Admin\InventoryController as AdminInventory;
use App\Http\Controllers\Admin\OfferController as AdminOffer;
use App\Http\Controllers\Admin\BannerController as AdminBanner;
use App\Http\Controllers\Admin\HomeSectionController as AdminHomeSection;
use App\Http\Controllers\Admin\PageController as AdminPage;
use App\Http\Controllers\Admin\ReviewController as AdminReview;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonial;

/*
|--------------------------------------------------------------------------
| CUSTOMER CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Customer\AuthController as CustomerAuth;
use App\Http\Controllers\Customer\HomeController as CustomerHome;
use App\Http\Controllers\Customer\ProductController as CustomerProduct;
use App\Http\Controllers\Customer\CartController as CustomerCart;
use App\Http\Controllers\Customer\CheckoutController as CustomerCheckout;
use App\Http\Controllers\Customer\WishlistController as CustomerWishlist;
use App\Http\Controllers\Customer\PageController as CustomerPage;
use App\Http\Controllers\Customer\AccountController as CustomerAccount;
use App\Http\Controllers\Customer\OrderController as CustomerOrder;
use App\Http\Controllers\Customer\UserController as CustomerUser;

/*
|--------------------------------------------------------------------------
| ADMIN PANEL ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | ADMIN AUTH
    |--------------------------------------------------------------------------
    */
    Route::get('/login', [AdminAuth::class, 'loginPage'])->name('admin.login');
    Route::post('/login', [AdminAuth::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuth::class, 'logout'])->name('admin.logout');

    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATED ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin.auth')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard/data', [AdminDashboard::class, 'getChartData'])->name('admin.dashboard.data');

        /*
        |--------------------------------------------------------------------------
        | CATEGORY MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('categories')->group(function () {
            Route::get('/', [AdminCategory::class, 'index'])->name('admin.categories.index');
            Route::get('/create', [AdminCategory::class, 'create'])->name('admin.categories.create');
            Route::get('/{id}/edit', [AdminCategory::class, 'edit'])->name('admin.categories.edit');
            Route::get('/{id}', [AdminCategory::class, 'show'])->name('admin.categories.show');
        });

        /*
        |--------------------------------------------------------------------------
        | BRAND MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('brands')->group(function () {
            Route::get('/', [AdminBrand::class, 'index'])->name('admin.brands.index');
        });

        /*
        |--------------------------------------------------------------------------
        | PRODUCT MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('products')->group(function () {
            Route::get('/', [AdminProduct::class, 'index'])->name('admin.products.index');
            Route::get('/create', [AdminProduct::class, 'create'])->name('admin.products.create');
            Route::get('/{product}/edit', [AdminProduct::class, 'edit'])->name('admin.products.edit');
            Route::post('/', [AdminProduct::class, 'store'])->name('admin.products.store');
            Route::put('/{product}', [AdminProduct::class, 'update'])->name('admin.products.update');
            Route::delete('/{product}', [AdminProduct::class, 'destroy'])->name('admin.products.destroy');

            Route::get('/attributes', [AdminProduct::class, 'attributes'])->name('admin.products.attributes');
            Route::get('/specifications', [AdminProduct::class, 'specifications'])->name('admin.products.specifications');
            Route::get('/tags', [AdminProduct::class, 'tags'])->name('admin.products.tags');
            Route::get('/variants', [AdminProduct::class, 'variants'])->name('admin.products.variants');
            Route::get('/category/{category}/specifications', [AdminProduct::class, 'getCategorySpecifications'])->name('admin.products.category.specifications');
            Route::get('/category/{category}/attributes', [AdminProduct::class, 'getCategoryAttributes'])->name('admin.products.category.attributes');
            Route::get('/search', [AdminProduct::class, 'search'])->name('admin.products.search');
        });

        /*
        |--------------------------------------------------------------------------
        | ORDER MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('orders')->name('admin.orders.')->group(function () {
            Route::get('/', [AdminOrder::class, 'index'])->name('index');
            Route::get('/data', [AdminOrder::class, 'getOrders'])->name('data');
            Route::get('/export', [AdminOrder::class, 'export'])->name('export');
            Route::get('/{order}', [AdminOrder::class, 'view'])->name('view');
            Route::post('/{order}/update-status', [AdminOrder::class, 'updateStatus'])->name('update-status');
            Route::post('/{order}/update-payment-status', [AdminOrder::class, 'updatePaymentStatus'])->name('update-payment-status');
            Route::post('/{order}/update-tracking', [AdminOrder::class, 'updateTracking'])->name('update-tracking');
            Route::delete('/{order}', [AdminOrder::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delete', [AdminOrder::class, 'bulkDelete'])->name('bulk-delete');
            Route::get('/{order}/invoice', [AdminOrder::class, 'printInvoice'])->name('invoice');
        });

        /*
        |--------------------------------------------------------------------------
        | MEDIA MANAGER
        |--------------------------------------------------------------------------
        */
        Route::prefix('media')->group(function () {
            Route::get('/', [AdminMedia::class, 'index'])->name('admin.media.index');
            Route::get('/data', [AdminMedia::class, 'getData'])->name('admin.media.data');
            Route::post('/upload', [AdminMedia::class, 'upload'])->name('admin.media.upload');
            Route::get('/{id}', [AdminMedia::class, 'show'])->name('admin.media.show');
            Route::put('/{id}', [AdminMedia::class, 'update'])->name('admin.media.update');
            Route::delete('/{id}', [AdminMedia::class, 'destroy'])->name('admin.media.destroy');
            Route::post('/bulk-delete', [AdminMedia::class, 'bulkDelete'])->name('admin.media.bulk-delete');
        });

        /*
        |--------------------------------------------------------------------------
        | OFFERS MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('offers')->group(function () {
            Route::get('/', [AdminOffer::class, 'index'])->name('admin.offers.index');
            Route::get('/create', [AdminOffer::class, 'create'])->name('admin.offers.create');
            Route::get('/edit', [AdminOffer::class, 'create'])->name('admin.offers.edit');
        });

        /*
        |--------------------------------------------------------------------------
        | TAX SETTINGS
        |--------------------------------------------------------------------------
        */
        Route::prefix('taxes')->group(function () {
            Route::get('/', [AdminTax::class, 'index'])->name('admin.taxes.index');
        });

        /*
        |--------------------------------------------------------------------------
        | USER MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('users')->name('admin.users.')->group(function () {
            // Pages
            Route::get('/', [AdminUser::class, 'index'])->name('index');
            Route::get('/create', [AdminUser::class, 'create'])->name('create');
            Route::get('/{user}/edit', [AdminUser::class, 'edit'])->name('edit');

            // AJAX / API (MUST BE BEFORE {user})
            Route::get('/data', [AdminUser::class, 'getCustomers'])->name('data');
            Route::post('/bulk-delete', [AdminUser::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/bulk-block', [AdminUser::class, 'bulkBlock'])->name('bulk-block');
            Route::get('/export', [AdminUser::class, 'export'])->name('export');

            Route::post('/{user}/toggle-status', [AdminUser::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{user}/toggle-block', [AdminUser::class, 'toggleBlock'])->name('toggle-block');

            // CRUD
            Route::post('/', [AdminUser::class, 'store'])->name('store');
            Route::put('/{user}', [AdminUser::class, 'update'])->name('update');
            Route::delete('/{user}', [AdminUser::class, 'destroy'])->name('destroy');
            Route::get('/{user}', [AdminUser::class, 'show'])->name('show');
        });

        /*
        |--------------------------------------------------------------------------
        | INVENTORY
        |--------------------------------------------------------------------------
        */
        Route::prefix('inventory')->group(function () {
            Route::get('/', [AdminInventory::class, 'index'])->name('admin.inventory.index');
            Route::get('/history', [AdminInventory::class, 'history'])->name('admin.inventory.history');
            Route::get('/{id}/update', [AdminInventory::class, 'updateStock'])->name('admin.inventory.update');
        });

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS
        |--------------------------------------------------------------------------
        */
        Route::get('/notifications', [AdminNotification::class, 'index'])->name('admin.notifications.index');

        /*
        |--------------------------------------------------------------------------
        | CRM
        |--------------------------------------------------------------------------
        */
        Route::prefix('crm')->group(function () {
            Route::get('/', [AdminCRM::class, 'index'])->name('admin.crm.index');
            Route::get('/popup', [AdminCRM::class, 'popup'])->name('admin.crm.popup');
            Route::get('/settings', [AdminCRM::class, 'settings'])->name('admin.crm.settings');

            // Banners
            Route::prefix('banners')->name('admin.banners.')->group(function () {
                Route::get('/', [AdminBanner::class, 'index'])->name('index');
                Route::get('/create', [AdminBanner::class, 'create'])->name('create');
                Route::post('/', [AdminBanner::class, 'store'])->name('store');
                Route::get('/{banner}/edit', [AdminBanner::class, 'edit'])->name('edit');
                Route::put('/{banner}', [AdminBanner::class, 'update'])->name('update');
                Route::delete('/{banner}', [AdminBanner::class, 'destroy'])->name('destroy');
                Route::post('/{banner}/toggle-status', [AdminBanner::class, 'toggleStatus'])->name('toggle-status');
            });

            // Home Sections
            Route::prefix('home-sections')->name('admin.home-sections.')->group(function () {
                Route::get('/', [AdminHomeSection::class, 'index'])->name('index');
                Route::get('/create', [AdminHomeSection::class, 'create'])->name('create');
                Route::post('/', [AdminHomeSection::class, 'store'])->name('store');
                Route::get('/{section}/edit', [AdminHomeSection::class, 'edit'])->name('edit');
                Route::put('/{section}', [AdminHomeSection::class, 'update'])->name('update');
                Route::delete('/{section}', [AdminHomeSection::class, 'destroy'])->name('destroy');
                Route::post('/{section}/toggle-status', [AdminHomeSection::class, 'toggleStatus'])->name('toggle-status');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | REPORTS
        |--------------------------------------------------------------------------
        */
        Route::prefix('reports')->group(function () {
            Route::get('/', [AdminReport::class, 'index'])->name('admin.reports.index');
            Route::get('/sales', [AdminReport::class, 'sales'])->name('admin.reports.sales');
            Route::get('/customers', [AdminReport::class, 'customers'])->name('admin.reports.customers');
            Route::get('/products', [AdminReport::class, 'products'])->name('admin.reports.products');
        });

        /*
        |--------------------------------------------------------------------------
        | SHIPPING
        |--------------------------------------------------------------------------
        */
        Route::prefix('shipping')->group(function () {
            Route::get('/', [AdminShipping::class, 'index'])->name('admin.shipping.index');
            Route::get('/charges', [AdminShipping::class, 'charges'])->name('admin.shipping.charges');
        });

        /*
        |--------------------------------------------------------------------------
        | SETTINGS
        |--------------------------------------------------------------------------
        */
        Route::get('/settings', [AdminSetting::class, 'index'])->name('admin.settings.index');

        /*
        |--------------------------------------------------------------------------
        | PAGES MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::resource('pages', AdminPage::class, ['as' => 'admin']);
        Route::resource('reviews', AdminReview::class, ['as' => 'admin']);
        Route::resource('testimonials', AdminTestimonial::class, ['as' => 'admin']);

        /*
        |--------------------------------------------------------------------------
        | API / AJAX ROUTES
        |--------------------------------------------------------------------------
        */
        Route::prefix('api')->group(function () {
            // Categories API
            Route::get('/categories/statistics', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'statistics'])->name('admin.api.categories.statistics');
            Route::get('/categories/dropdown', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'dropdown'])->name('admin.api.categories.dropdown');
            Route::post('/categories/{id}/status', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'updateStatus'])->name('admin.api.categories.status');
            Route::post('/categories/bulk-status', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'bulkUpdateStatus'])->name('admin.api.categories.bulk-status');
            Route::post('/categories/bulk-delete', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'bulkDelete'])->name('admin.api.categories.bulk-delete');
            Route::apiResource('categories', \App\Http\Controllers\Api\Admin\CategoryController::class)->names('admin.api.categories');
            
            // Attributes API
            Route::get('/attributes/statistics', [\App\Http\Controllers\Api\Admin\AttributeController::class, 'statistics'])->name('admin.api.attributes.statistics');
            Route::get('/attributes/dropdown', [\App\Http\Controllers\Api\Admin\AttributeController::class, 'dropdown'])->name('admin.api.attributes.dropdown');
            Route::post('/attributes/{id}/toggle-status', [\App\Http\Controllers\Api\Admin\AttributeController::class, 'toggleStatus'])->name('admin.api.attributes.toggle-status');
            Route::apiResource('attributes', \App\Http\Controllers\Api\Admin\AttributeController::class)->names('admin.api.attributes');
            
            // Specifications API
            Route::get('/specifications/statistics', [\App\Http\Controllers\Api\Admin\SpecificationController::class, 'statistics'])->name('admin.api.specifications.statistics');
            Route::get('/specifications/dropdown', [\App\Http\Controllers\Api\Admin\SpecificationController::class, 'dropdown'])->name('admin.api.specifications.dropdown');
            Route::post('/specifications/{id}/toggle-status', [\App\Http\Controllers\Api\Admin\SpecificationController::class, 'toggleStatus'])->name('admin.api.specifications.toggle-status');
            Route::apiResource('specifications', \App\Http\Controllers\Api\Admin\SpecificationController::class)->names('admin.api.specifications');
            
            // Specification Groups API
            Route::get('/specification-groups/statistics', [\App\Http\Controllers\Api\Admin\SpecificationGroupController::class, 'statistics'])->name('admin.api.specification-groups.statistics');
            Route::get('/specification-groups/dropdown', [\App\Http\Controllers\Api\Admin\SpecificationGroupController::class, 'dropdown'])->name('admin.api.specification-groups.dropdown');
            Route::apiResource('specification-groups', \App\Http\Controllers\Api\Admin\SpecificationGroupController::class)->names('admin.api.specification-groups');
            
            // Tags API
            Route::get('/tags/statistics', [\App\Http\Controllers\Api\Admin\TagController::class, 'statistics'])->name('admin.api.tags.statistics');
            Route::get('/tags/popular', [\App\Http\Controllers\Api\Admin\TagController::class, 'popular'])->name('admin.api.tags.popular');
            Route::get('/tags/dropdown', [\App\Http\Controllers\Api\Admin\TagController::class, 'dropdown'])->name('admin.api.tags.dropdown');
            Route::post('/tags/{id}/status', [\App\Http\Controllers\Api\Admin\TagController::class, 'updateStatus'])->name('admin.api.tags.status');
            Route::post('/tags/{id}/featured', [\App\Http\Controllers\Api\Admin\TagController::class, 'updateFeatured'])->name('admin.api.tags.featured');
            Route::post('/tags/bulk-status', [\App\Http\Controllers\Api\Admin\TagController::class, 'bulkStatus'])->name('admin.api.tags.bulk-status');
            Route::post('/tags/bulk-featured', [\App\Http\Controllers\Api\Admin\TagController::class, 'bulkFeatured'])->name('admin.api.tags.bulk-featured');
            Route::post('/tags/bulk-delete', [\App\Http\Controllers\Api\Admin\TagController::class, 'bulkDelete'])->name('admin.api.tags.bulk-delete');
            Route::apiResource('tags', \App\Http\Controllers\Api\Admin\TagController::class)->names('admin.api.tags');

            // Products API
            Route::get('/products/dropdown', [\App\Http\Controllers\Api\Admin\ProductController::class, 'dropdown'])->name('admin.api.products.dropdown');

            // Media API
            Route::get('/media', [AdminMedia::class, 'getData'])->name('admin.api.media.index');

            // Brands API
            Route::get('/brands/statistics', [\App\Http\Controllers\Api\Admin\BrandController::class, 'statistics'])->name('admin.api.brands.statistics');
            Route::get('/brands/dropdown', [\App\Http\Controllers\Api\Admin\BrandController::class, 'dropdown'])->name('admin.api.brands.dropdown');
            Route::post('/brands/{id}/status', [\App\Http\Controllers\Api\Admin\BrandController::class, 'updateStatus'])->name('admin.api.brands.status');
            Route::post('/brands/{id}/featured', [\App\Http\Controllers\Api\Admin\BrandController::class, 'updateFeatured'])->name('admin.api.brands.featured');
            Route::post('/brands/bulk-status', [\App\Http\Controllers\Api\Admin\BrandController::class, 'bulkStatus'])->name('admin.api.brands.bulk-status');
            Route::post('/brands/bulk-featured', [\App\Http\Controllers\Api\Admin\BrandController::class, 'bulkFeatured'])->name('admin.api.brands.bulk-featured');
            Route::post('/brands/bulk-delete', [\App\Http\Controllers\Api\Admin\BrandController::class, 'bulkDelete'])->name('admin.api.brands.bulk-delete');
            Route::apiResource('brands', \App\Http\Controllers\Api\Admin\BrandController::class)->names('admin.api.brands');

            // Tax Rates API
            Route::get('/tax-rates/statistics', [\App\Http\Controllers\Api\Admin\TaxRateController::class, 'statistics'])->name('admin.api.tax-rates.statistics');
            Route::get('/tax-rates/types', [\App\Http\Controllers\Api\Admin\TaxRateController::class, 'types'])->name('admin.api.tax-rates.types');
            Route::get('/tax-rates/scopes', [\App\Http\Controllers\Api\Admin\TaxRateController::class, 'scopes'])->name('admin.api.tax-rates.scopes');
            Route::post('/tax-rates/{id}/toggle-status', [\App\Http\Controllers\Api\Admin\TaxRateController::class, 'toggleStatus'])->name('admin.api.tax-rates.toggle-status');
            Route::post('/tax-rates/bulk-status', [\App\Http\Controllers\Api\Admin\TaxRateController::class, 'bulkStatus'])->name('admin.api.tax-rates.bulk-status');
            Route::post('/tax-rates/bulk-delete', [\App\Http\Controllers\Api\Admin\TaxRateController::class, 'bulkDelete'])->name('admin.api.tax-rates.bulk-delete');
            Route::post('/tax-rates/calculate', [\App\Http\Controllers\Api\Admin\TaxRateController::class, 'calculate'])->name('admin.api.tax-rates.calculate');
            Route::apiResource('tax-rates', \App\Http\Controllers\Api\Admin\TaxRateController::class)->names('admin.api.tax-rates');

            // Tax Classes API
            Route::get('/tax-classes/statistics', [\App\Http\Controllers\Api\Admin\TaxClassController::class, 'statistics'])->name('admin.api.tax-classes.statistics');
            Route::get('/tax-classes/dropdown', [\App\Http\Controllers\Api\Admin\TaxClassController::class, 'dropdown'])->name('admin.api.tax-classes.dropdown');
            Route::post('/tax-classes/{id}/toggle-default', [\App\Http\Controllers\Api\Admin\TaxClassController::class, 'toggleDefault'])->name('admin.api.tax-classes.toggle-default');
            Route::post('/tax-classes/bulk-delete', [\App\Http\Controllers\Api\Admin\TaxClassController::class, 'bulkDelete'])->name('admin.api.tax-classes.bulk-delete');
            Route::apiResource('tax-classes', \App\Http\Controllers\Api\Admin\TaxClassController::class)->names('admin.api.tax-classes');

            // Offers API
            Route::get('/offers/statistics', [\App\Http\Controllers\Api\Admin\OfferController::class, 'statistics'])->name('admin.api.offers.statistics');
            Route::get('/offers/types', [\App\Http\Controllers\Api\Admin\OfferController::class, 'types'])->name('admin.api.offers.types');
            Route::get('/offers/dropdown', [\App\Http\Controllers\Api\Admin\OfferController::class, 'dropdown'])->name('admin.api.offers.dropdown');
            Route::get('/offers/validate-code', [\App\Http\Controllers\Api\Admin\OfferController::class, 'validateCode'])->name('admin.api.offers.validate-code');
            Route::post('/offers/{id}/status', [\App\Http\Controllers\Api\Admin\OfferController::class, 'updateStatus'])->name('admin.api.offers.toggle-status');
            Route::post('/offers/{id}/auto-apply', [\App\Http\Controllers\Api\Admin\OfferController::class, 'updateAutoApply'])->name('admin.api.offers.toggle-auto-apply');
            Route::post('/offers/bulk-delete', [\App\Http\Controllers\Api\Admin\OfferController::class, 'bulkDelete'])->name('admin.api.offers.bulk-delete');
            Route::post('/offers/bulk-status', [\App\Http\Controllers\Api\Admin\OfferController::class, 'bulkStatus'])->name('admin.api.offers.bulk-status');
            Route::apiResource('offers', \App\Http\Controllers\Api\Admin\OfferController::class)->names('admin.api.offers');

            // Settings API
            Route::get('/settings/groups', [\App\Http\Controllers\Api\Admin\SettingController::class, 'groups'])->name('admin.api.settings.groups');
            Route::post('/settings/bulk-update', [\App\Http\Controllers\Api\Admin\SettingController::class, 'bulkUpdate'])->name('admin.api.settings.bulk-update');
            Route::post('/settings/reset', [\App\Http\Controllers\Api\Admin\SettingController::class, 'reset'])->name('admin.api.settings.reset');

            // Admin Profile API
            Route::post('/profile/update', [\App\Http\Controllers\Api\Admin\AdminApiAuthController::class, 'updateProfile'])->name('admin.api.profile.update');

            // Media Upload (ensure it's accessible)
            Route::post('/media/upload', [AdminMedia::class, 'upload'])->name('admin.api.media.upload');
        });
    });
});

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/
Route::name('customer.')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | HOME PAGE
    |--------------------------------------------------------------------------
    */
    Route::get('/', [CustomerHome::class, 'index'])->name('home.index');

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER AUTH
    |--------------------------------------------------------------------------
    */
    Route::get('/login', [CustomerAuth::class, 'loginPage'])->name('login');
    Route::post('/login', [CustomerAuth::class, 'login'])->name('login.submit');
    
    Route::get('/register', [CustomerAuth::class, 'registerPage'])->name('register');
    Route::post('/register', [CustomerAuth::class, 'register'])->name('register.submit');
    
    Route::get('/verify', [CustomerAuth::class, 'verifyPage'])->name('verify');
    Route::post('/verify', [CustomerAuth::class, 'verify'])->name('verify.submit');
    Route::post('/resend-otp', [CustomerAuth::class, 'resendOTP'])->name('otp.resend');
    Route::post('/change-email', [CustomerAuth::class, 'changeEmail'])->name('auth.change-email');
    
    Route::post('/logout', [CustomerAuth::class, 'logout'])->name('logout');
    
    Route::get('/forgot-password', [CustomerAuth::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [CustomerAuth::class, 'sendResetLinkEmail'])->name('forgot-password.submit');
    
    Route::get('/reset-password/{token}', [CustomerAuth::class, 'showResetPasswordForm'])->name('reset-password');
    Route::post('/reset-password', [CustomerAuth::class, 'resetPassword'])->name('reset-password.update');

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */
    Route::get('/products', [CustomerProduct::class, 'listing'])->name('products.list');
    Route::get('/category/{slug}', [CustomerProduct::class, 'category'])->name('category.products');
    Route::get('/product/{slug}', [CustomerProduct::class, 'details'])->name('products.details');
    Route::post('/product/{slug}/review', [CustomerProduct::class, 'storeReview'])->name('products.review.store');
    Route::get('/search', [CustomerProduct::class, 'search'])->name('products.search');
  Route::get('/rugs', [CustomerProduct::class, 'rugs'])->name('products.rugs');
    Route::get('/products/{slug}/quick-view', [CustomerProduct::class, 'quickView'])->name('products.quick-view');

    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */
    Route::prefix('cart')->group(function () {
        Route::get('/', [CustomerCart::class, 'index'])->name('cart');
        Route::post('/add', [CustomerCart::class, 'addItem'])->name('cart.add');
        Route::put('/update/{cartItemId}', [CustomerCart::class, 'updateQuantity'])->name('cart.update');
        Route::delete('/remove/{cartItemId}', [CustomerCart::class, 'removeItem'])->name('cart.remove');
        Route::post('/apply-coupon', [CustomerCart::class, 'applyCoupon'])->name('cart.apply-coupon');
        Route::post('/remove-coupon', [CustomerCart::class, 'removeCoupon'])->name('cart.remove-coupon');
        Route::post('/sync', [CustomerCart::class, 'syncCart'])->name('cart.sync');
        Route::get('/summary', [CustomerCart::class, 'getCartSummary'])->name('cart.summary');
        Route::get('/count', [CustomerCart::class, 'getCartCount'])->name('cart.count');
        Route::delete('/clear', [CustomerCart::class, 'clearCart'])->name('cart.clear');
    });

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */
    Route::middleware(['customer.auth'])->prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CustomerCheckout::class, 'index'])->name('index');
        Route::get('/payment', [CustomerCheckout::class, 'payment'])->name('payment');
        Route::get('/confirmation/{order?}', [CustomerCheckout::class, 'confirmation'])->name('confirmation');
        
        Route::post('/process', [CustomerCheckout::class, 'processCheckout'])->name('process');
        Route::post('/shipping/check', [CustomerCheckout::class, 'checkShipping'])->name('shipping.check');
        Route::post('/payment/callback', [CustomerCheckout::class, 'paymentCallback'])->name('payment.callback');
        Route::get('/payment/failed', [CustomerCheckout::class, 'paymentFailed'])->name('payment.failed');
        Route::post('/buy-now', [CustomerCheckout::class, 'buyNow'])->name('buy.now');
        Route::post('/razorpay/order', [CustomerCheckout::class, 'createRazorpayOrder'])->name('razorpay.order');
    });

    /*
    |--------------------------------------------------------------------------
    | WISHLIST
    |--------------------------------------------------------------------------
    */
    // Wishlist Routes
    Route::middleware(['customer.auth'])->prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [CustomerWishlist::class, 'index'])->name('index');

        // Item management
        Route::post('/toggle', [CustomerWishlist::class, 'add'])->name('toggle');
        Route::post('/remove', [CustomerWishlist::class, 'remove'])->name('remove');
        Route::post('/remove-multiple', [CustomerWishlist::class, 'removeMultiple'])->name('remove.multiple');
        Route::post('/move-to-cart', [CustomerWishlist::class, 'moveToCart'])->name('move-to-cart');
        Route::post('/move-all-to-cart', [CustomerWishlist::class, 'moveAllToCart'])->name('move-all-to-cart');
        Route::post('/clear', [CustomerWishlist::class, 'clear'])->name('clear');

        // Wishlist management
        Route::post('/create', [CustomerWishlist::class, 'create'])->name('create');
        Route::put('/{id}', [CustomerWishlist::class, 'update'])->name('update');
        Route::delete('/{id}', [CustomerWishlist::class, 'delete'])->name('delete');
        Route::post('/{id}/share', [CustomerWishlist::class, 'share'])->name('share');
        Route::post('/{id}/add-item', [CustomerWishlist::class, 'addItemToWishlist'])->name('add.item');

        // Data endpoints
        Route::get('/count', [CustomerWishlist::class, 'count'])->name('count');
        Route::get('/items', [CustomerWishlist::class, 'getWishlistItems'])->name('items');
        Route::get('/wishlists', [CustomerWishlist::class, 'getWishlists'])->name('wishlists');
    });

    // Public shared wishlist (no auth required)
    Route::get('/wishlist/shared/{id}', [CustomerWishlist::class, 'shared'])->name('wishlist.shared');

    /*
    |--------------------------------------------------------------------------
    | CMS STATIC PAGES
    |--------------------------------------------------------------------------
    */
    Route::prefix('page')->group(function () {
        // Dynamic Page Route
        Route::get('/{slug}', [CustomerPage::class, 'show'])->name('page.show');
        
        // Named routes for backward compatibility
        Route::get('/about', [CustomerPage::class, 'show'])->defaults('slug', 'about-us')->name('page.about');
        Route::get('/contact', [CustomerPage::class, 'show'])->defaults('slug', 'contact-us')->name('page.contact');
        Route::get('/faq', [CustomerPage::class, 'show'])->defaults('slug', 'faq')->name('page.faq');
        Route::get('/terms', [CustomerPage::class, 'show'])->defaults('slug', 'terms-and-conditions')->name('page.terms');
        Route::get('/privacy-policy', [CustomerPage::class, 'show'])->defaults('slug', 'privacy-policy')->name('page.privacy');
        Route::get('/shipping-policy', [CustomerPage::class, 'show'])->defaults('slug', 'shipping-policy')->name('page.shipping-policy');
        Route::get('/size-guide', [CustomerPage::class, 'show'])->defaults('slug', 'size-guide')->name('page.size-guide');
    });

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER ACCOUNT (LOGGED-IN AREA)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['customer.auth'])->prefix('account')->name('account.')->group(function () {
        Route::get('/profile', [CustomerAccount::class, 'profile'])->name('profile');
        Route::get('/orders', [CustomerOrder::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [CustomerOrder::class, 'orderDetails'])->name('orders.details');
        Route::get('/addresses', [CustomerAccount::class, 'addresses'])->name('addresses');
        Route::get('/change-password', [CustomerAccount::class, 'changePassword'])->name('change-password');
        Route::get('/orderfailed', [CustomerOrder::class, 'orderfailed'])->name('orderfailed');
        Route::get('/ordersuccess', [CustomerOrder::class, 'ordersuccess'])->name('ordersuccess');
        
        Route::get('/orders/filter/{status}', [CustomerOrder::class, 'filterOrders'])->name('orders.filter');
        Route::post('/orders/{id}/cancel', [CustomerOrder::class, 'cancelOrder'])->name('orders.cancel');
        Route::get('/orders/{id}/invoice', [CustomerOrder::class, 'downloadInvoice'])->name('orders.download-invoice');
        
        // Address management
        Route::post('/addresses', [CustomerAccount::class, 'storeAddress'])->name('addresses.store');
        Route::put('/addresses/{id}', [CustomerAccount::class, 'updateAddress'])->name('addresses.update');
        Route::delete('/addresses/{id}', [CustomerAccount::class, 'deleteAddress'])->name('addresses.delete');
        Route::post('/addresses/{id}/set-default', [CustomerAccount::class, 'setDefaultAddress'])->name('addresses.set-default');
        
        // Change password
        Route::post('/change-password', [CustomerAccount::class, 'updatePassword'])->name('update-password');
    });
});

/*
|--------------------------------------------------------------------------
| FALLBACK 404 PAGE
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('customer.errors.404');
})->name('customer.error.404');

// Temporary route to fix and debug storage link on live server
Route::get('/fix-storage', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');
    
    echo "<h1>Storage Debugger</h1>";
    echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
    echo "<p><strong>APP_URL:</strong> " . config('app.url') . "</p>";
    echo "<p><strong>Target (storage/app/public):</strong> $target</p>";
    echo "<p><strong>Link (public/storage):</strong> $link</p>";

    echo "<h2>Status</h2>";
    if (file_exists($target)) {
        echo "<p style='color:green'>&#10004; Target directory exists.</p>";
    } else {
        echo "<p style='color:red'>&#10008; Target directory DOES NOT exist!</p>";
    }

    if (file_exists($link)) {
        echo "<p style='color:green'>&#10004; Link exists.</p>";
        if (is_link($link)) {
            echo "<p>Type: Symlink</p>";
            echo "<p>Points to: " . readlink($link) . "</p>";
        } else {
            echo "<p style='color:orange'>Type: Directory (Not a symlink! This might be the problem if it's an actual folder)</p>";
        }
    } else {
        echo "<p style='color:red'>&#10008; Link DOES NOT exist.</p>";
    }

    echo "<h2>Actions</h2>";
    
    try {
        if (file_exists($link) && !is_link($link)) {
            echo "<p>Attempting to remove existing 'storage' directory...</p>";
            // Use rmdir or rename if it's a directory
            rename($link, public_path('storage_backup_' . time()));
            echo "<p>Renamed existing 'storage' directory to backup.</p>";
        }

        if (file_exists($link) && is_link($link)) {
             unlink($link);
             echo "<p>Removed old symlink.</p>";
        }

        app('files')->link($target, $link);
        echo "<p style='color:green; font-weight:bold'>&#10004; Check: Symlink created successfully!</p>";
    } catch (\Exception $e) {
        echo "<p style='color:red'>Error creating symlink: " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>Test File</h2>";
    $testFile = 'test_debug.txt';
    try {
        \Illuminate\Support\Facades\Storage::disk('public')->put($testFile, 'Debug test content ' . time());
        $url = \Illuminate\Support\Facades\Storage::disk('public')->url($testFile);
        echo "<p>Created test file. URL: <a href='$url' target='_blank'>$url</a></p>";
        echo "<p>Click the link above. If it works, images should work. If 404, check checking APP_URL matches domain.</p>";
    } catch (\Exception $e) {
         echo "<p style='color:red'>Failed to create test file: " . $e->getMessage() . "</p>";
    }
});