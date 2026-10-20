<?php


use App\Http\Controllers\PaymentCallbackController;
use App\Livewire\Cart;
use App\Livewire\FrontStore;
use App\Livewire\Product;
use Illuminate\Support\Facades\Route;


Route::get('/', FrontStore::class)->name('home');
Route::get('products/trending', function () {
    $redis = new \Predis\Client([
        'scheme' => 'tcp',
        'host' => '127.0.0.1',
        'port' => 6379,
    ]);

    $trends = $redis->zrevrange('trend_products', 0, 5);
    /*
    اگر داده‌ها از Redis می‌آیند، مطمئن شو که json_decode به درستی کار می‌کند.
    اگر داده‌ها از دیتابیس می‌آیند، معمولاً نیازی به json_decode نیست مگر اینکه فیلدها به صورت JSON ذخیره شده باشند.
    */
    $trends = collect($trends)
        ->map(fn($item) => json_decode($item, true)) // تبدیل JSON به آرایه
        ->filter() // حذف مقادیر null (در صورت وجود خطا در decode)
        ->values() // ریست کردن ایندکس‌ها
        ->all(); // تبدیل به آرایه ساده

    /*
     * تابع hydrate یکی از روش‌های سریع برای ایجاد نمونه‌های مدل Eloquent از داده‌های موجود است:
    * برخلاف create یا new Model، hydrate مستقیماً مقادیر را به ویژگی‌های (attributes) مدل نسبت می‌دهد.
    * این روش بسیار سریع‌تر از حلقه foreach و ایجاد دستی اشیاء است.
    * مدل‌های بازگشتی، روابط (Relations) و تایپ‌شدگی (Casting) همچنان کار می‌کنند.
    */
    $trends = \App\Models\Product::hydrate($trends);
    return $trends->toArray();

});

Route::get('product/{product}', Product::class)->name('product');

Route::get('/cart', Cart::class)->name('cart');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    // Route بازگشت از بانک
    Route::get('/payment/callback', PaymentCallbackController::class)->name('payment.callback');
});


/*Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});*/
