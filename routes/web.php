<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ProfileController;

use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\WishlistController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\OrderController as ShopOrderController;
use App\Http\Controllers\Shop\ReviewController as ShopReviewController;
use App\Http\Controllers\Shop\SearchController;
use App\Http\Controllers\Shop\ContactController;
use App\Http\Controllers\Shop\NewsletterController as ShopNewsletterController;
use App\Http\Controllers\Shop\TrackOrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
// ─────────────────────────────────────────────────────────────
// STORAGE LINK (for cPanel — no CLI access needed)
// ─────────────────────────────────────────────────────────────
Route::get('/storage-link', function () {
    if (! file_exists(public_path('storage'))) {
        \Artisan::call('storage:link');
        return 'Storage linked successfully!';
    }
    return 'Storage already linked.';
})->name('storage.link');

// ─────────────────────────────────────────────────────────────
// SITEMAP & ROBOTS
// ─────────────────────────────────────────────────────────────
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [HomeController::class, 'robots'])->name('robots');

// ─────────────────────────────────────────────────────────────
// AUTHENTICATION ROUTES
// ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
    Route::get('/forgot-password',  [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])->name('password.email');
    Route::get('/reset-password/{token}',  [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Email Verification — DISABLED (will be re-enabled later)

// ─────────────────────────────────────────────────────────────
// CUSTOMER ACCOUNT
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/profile',          [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password',         [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::get('/orders',           [ShopOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}',   [ShopOrderController::class, 'show'])->name('orders.show');
    Route::get('/wishlist',         [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/{product}',   [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/addresses',        [ProfileController::class, 'addresses'])->name('addresses');
    Route::post('/addresses',       [ProfileController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/addresses/{address}',  [ProfileController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [ProfileController::class, 'deleteAddress'])->name('addresses.destroy');
});

// ─────────────────────────────────────────────────────────────
// SHOP — FRONTEND
// ─────────────────────────────────────────────────────────────
Route::get('/',          [HomeController::class, 'index'])->name('home');
Route::get('/shop',      [ShopProductController::class, 'index'])->name('shop');
Route::get('/shop/category/{category:slug}',  [ShopProductController::class, 'category'])->name('shop.category');
Route::get('/shop/{product:slug}',            [ShopProductController::class, 'show'])->name('shop.product');
Route::get('/search',    [SearchController::class, 'index'])->name('search');
Route::get('/contact',   [ContactController::class, 'showForm'])->name('contact');
Route::post('/contact',  [ContactController::class, 'send'])->name('contact.send');
Route::post('/newsletter/subscribe', [ShopNewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Reviews (require auth)
Route::middleware('auth')->group(function () {
    Route::post('/shop/{product:slug}/review', [ShopReviewController::class, 'store'])->name('review.store');
});

// Track Order
Route::get('/track-order', [TrackOrderController::class, 'index'])->name('track-order.index');
Route::post('/track-order', [TrackOrderController::class, 'track'])->name('track-order.track');

// Cart
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/',             [CartController::class, 'index'])->name('index');
    Route::get('/count',        [CartController::class, 'count'])->name('count');
    Route::post('/add',         [CartController::class, 'add'])->name('add');
    Route::put('/update/{item}',[CartController::class, 'update'])->name('update');
    Route::post('/update-item-qty', [CartController::class, 'updateItemQty'])->name('update-item-qty');
    Route::delete('/remove/{item}', [CartController::class, 'remove'])->name('remove');
    Route::post('/coupon',      [CartController::class, 'applyCoupon'])->name('coupon');
    Route::delete('/coupon',    [CartController::class, 'removeCoupon'])->name('coupon.remove');
});

// Checkout
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/',     [CheckoutController::class, 'index'])->name('index');
    Route::post('/',    [CheckoutController::class, 'placeOrder'])->name('place');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});

// ─────────────────────────────────────────────────────────────
// ADMIN PANEL
// ─────────────────────────────────────────────────────────────
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Catalog
    Route::middleware('permission:manage_categories')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::post('categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('categories.toggle');
    });

    Route::middleware('permission:manage_products')->group(function () {
        Route::get('products/datatable',          [ProductController::class, 'datatable'])->name('products.datatable');
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/toggle',  [ProductController::class, 'toggle'])->name('products.toggle');
        Route::post('products/{product}/images',  [ProductController::class, 'uploadImages'])->name('products.images');
        Route::delete('products/images/{image}',  [ProductController::class, 'deleteImage'])->name('products.images.destroy');
    });

    Route::middleware('permission:manage_tags')->group(function () {
        Route::resource('tags', TagController::class)->except('show');
    });

    // Orders
    Route::middleware('permission:manage_orders')->group(function () {
        Route::get('orders/datatable',          [OrderController::class, 'datatable'])->name('orders.datatable');
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
        Route::put('orders/{order}/status',   [OrderController::class, 'updateStatus'])->name('orders.status');
        Route::get('orders/{order}/invoice',  [OrderController::class, 'invoice'])->name('orders.invoice');
        Route::get('orders/create',           [OrderController::class, 'create'])->name('orders.create');
        Route::post('orders',                 [OrderController::class, 'store'])->name('orders.store');
    });

    // Customers
    Route::middleware('permission:manage_customers')->group(function () {
        Route::get('customers/datatable',     [CustomerController::class, 'datatable'])->name('customers.datatable');
        Route::resource('customers', CustomerController::class)->only(['index', 'show']);
        Route::post('customers/{user}/ban',   [CustomerController::class, 'ban'])->name('customers.ban');
        Route::post('customers/{user}/unban', [CustomerController::class, 'unban'])->name('customers.unban');
        Route::post('customers/{user}/email', [CustomerController::class, 'sendEmail'])->name('customers.email');
    });

    // Admin User & Roles Management (super_admin only)
    Route::middleware('can:manage_admin_users')->group(function () {
        Route::resource('users', UserController::class)->except('show');
        Route::resource('roles', RoleController::class)->except('show');
    });

    // Marketing
    Route::middleware('permission:manage_coupons')->group(function () {
        Route::resource('coupons', CouponController::class);
        Route::post('coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle');
    });
    Route::middleware('permission:manage_sliders')->group(function () {
        Route::resource('sliders', SliderController::class)->except('show');
        Route::post('sliders/reorder', [SliderController::class, 'reorder'])->name('sliders.reorder');
    });
    Route::middleware('permission:manage_newsletters')->group(function () {
        Route::resource('newsletters', NewsletterController::class)->only(['index', 'destroy']);
        Route::get('newsletters/export', [NewsletterController::class, 'export'])->name('newsletters.export');
    });
    Route::middleware('permission:manage_inquiries')->group(function () {
        Route::resource('inquiries', InquiryController::class)->only(['index', 'show', 'destroy']);
        Route::post('inquiries/{inquiry}/reply', [InquiryController::class, 'reply'])->name('inquiries.reply');
    });

    // Reviews
    Route::middleware('permission:manage_reviews')->group(function () {
        Route::resource('reviews', ReviewController::class)->only(['index', 'destroy']);
        Route::post('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    });

    // Reports
    Route::middleware('permission:manage_reports')->group(function () {
        Route::get('reports/sales',    [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('reports/orders',   [ReportController::class, 'orders'])->name('reports.orders');
        Route::get('reports/export',   [ReportController::class, 'export'])->name('reports.export');
    });

    // Shipping & Cities
    Route::middleware('permission:manage_shipping')->group(function () {
        Route::resource('cities', CityController::class)->except(['show']);
    });

    // Settings & Configuration
    Route::middleware('permission:manage_settings')->group(function () {
        Route::get('settings',          [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings',         [SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/mail-test', [SettingController::class, 'testMail'])->name('settings.mail.test');
        Route::resource('payment-methods', App\Http\Controllers\Admin\PaymentMethodController::class)->except('show');
    });

    // Activity Log
    Route::middleware('permission:manage_activity_log')->group(function () {
        Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity.index');
    });
});
