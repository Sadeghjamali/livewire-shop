<?php

namespace App\Http\Controllers;

use App\Actions\WebShop\Payment\VerifyPayment;
use App\Livewire\Cart;
use Illuminate\Http\Request;
use Laravel\Jetstream\InteractsWithBanner;

class PaymentCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $orderId = $request->query('order_id');
        $status = $request->query('Status'); // زرین‌پال پارامتر را با حروف بزرگ می‌فرستد
        $authority = $request->query('Authority');
        $component = app(Cart::class);
        // Mount را صدا می‌زنیم تا متغیرها ست شوند
        $component->mount();

        // ذخیره order_id در session یا redirect param برای مرحله بعد
        session(['current_order_id' => $orderId]);

        $verifyPayment = new VerifyPayment();
        $component->verify($verifyPayment, $orderId, $authority, $status);
        return redirect()->route('cart');
    }
}
