<?php

namespace App\Livewire;


use App\Actions\WebShop\Payment\CreatePaymentIntent;
use App\Actions\WebShop\Payment\VerifyPayment;
use App\Models\Order;
use Database\Factories\CartFactory;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Attributes\Layout;
use Livewire\Component;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class Cart extends Component
{
    public $total_amount;
    public $description = 'پرداخت سفارش خرید';
    public $email = '';
    public $mobile = '';
    public $callbackUrl;
    public $merchantId = '68B14720-8788-42D5-9A9D-C1E1033715C5';
    // وضعیت‌های مختلف فرآیند
    public $status = 'pending'; // pending, success, error, redirecting
    public $authority = '';

    public function mount(): void
    {
        $this->loadCart();
    }

    protected function loadCart()
    {
        // اگر کاربر لاگین است، اطلاعات او را بگیرید
        if (auth()->check()) {
            $user = auth()->user();
            $this->email = $user->email ?? '';
            $this->mobile = $user->mobile ?? '';
        }
        $this->total_amount = $this->cart->total;
    }

    public function getCartProperty()
    {
        return CartFactory::make();
    }

    public function getItemsProperty()
    {
        return $this->cart->items()->with('product')->get();
    }

    public function delete($itemId): void
    {
        $this->cart->items()->where('id', $itemId)->delete();
        $this->dispatch('productRemoveItemCart');
    }

    public function increment($itemId): void
    {
        if($this->cart->items !== null){
            $this->cart->items()->find($itemId)->increment('quantity');
            $this->dispatch('incrementItemCart');
        }
    }

    public function decrement($itemId): void
    {
        $item = $this->cart->items()->find($itemId);
        if ($item->quantity > 1) {
            $item->decrement('quantity');
        }
        $this->dispatch('decrementItemCart');
    }

    /**
     * شروع فرآیند پرداخت و دریافت Authority از زرین‌پال
     */
    public function initiatePayment(CreatePaymentIntent $action): void
    {
//        'status' = 'pending, processing, shipped, completed'
        // جستجوی سفارش باز (Pending) برای این کاربر
        // اگر کاربر قبلاً چیزی در سبد داشته که هنوز پرداخت نکرده، همان را برمی‌گردانیم
        $userId = auth()->id();
        $order = Order::query()
            ->where('user_id', $userId)
            ->whereIn('status',['pending','processing'])
            ->first();
        if (!$order) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => $this->cart->total,
                'status' => 'pending',
            ]);
        }

        foreach ($this->items as $item) {
            $order->items()->create([
                'product_id' => $item->product->id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }
        // تغییر وضعیت به processing برای هدایت به درگاه
        if($order->status === 'pending'){
            $order->status = 'processing';
            $order->save();
        }

        // تنظیم پارامترهای درخواست
        $callbackUrl = url('/payment/callback') . '?order_id=' . $order->id;
        $result = $action->execute(
            amount: $this->cart->total,
            callbackUrl: $callbackUrl,
            merchantId: $this->merchantId,
            email: auth()->user()->email ?? '',
            mobile: auth()->user()->mobile ?? '',
        );

        if ($result['success']) {
            // ریدایرکت به درگاه
            $this->redirect($result['url']);
        } else {
            $this->dangerBanner($result['message']);
        }
    }


public function verify(
    VerifyPayment $action,
    $order_id,
    $authority,
    $status,
    $amount
): void {
    $sessionOrderId = session('current_order_id');

    // 1. شناسه سفارش باید در Session وجود داشته باشد
    // و با شناسه سفارش callback مطابقت کند.
    if (!$sessionOrderId || (string) $sessionOrderId !== (string) $order_id) {
        abort(403, 'شناسه سفارش معتبر نیست.');
    }

    $outcome = DB::transaction(function () use (
        $action,
        $order_id,
        $authority,
        $status
    ) {
        // 2. قفل سفارش برای جلوگیری از پردازش هم‌زمان callbackها
        $order = Order::query()
            ->whereKey($order_id)
            ->lockForUpdate()
            ->first();

        if (!$order) {
            abort(404, 'سفارش پیدا نشد.');
        }

        // 3. بررسی مالکیت سفارش
        if ((int) $order->user_id !== (int) Auth::id()) {
            abort(403, 'این سفارش متعلق به شما نیست.');
        }

        // 4. جلوگیری از تأیید مجدد سفارش پرداخت‌شده
        if ($order->status === 'paid') {
            return ['type' => 'already_paid'];
        }

        // 5. فقط سفارش در انتظار پرداخت قابل بررسی است
        if ($order->status !== 'processing') {
            abort(403, 'وضعیت سفارش معتبر نیست.');
        }

        // 6. لغو پرداخت
        if ($status === 'Cancel') {
            $order->status = 'failed';
            $order->save();

            return ['type' => 'cancelled'];
        }

        // 7. مبلغ باید از رکورد سفارش خوانده شود.
        // اگر نام ستون در پروژه total_amount نیست، آن را اصلاح کن.
        $result = $action->execute(
            authority: $authority,
            amount: (int) $order->total_amount,
            status: $status,
        );

        if (empty($result['success'])) {
            return ['type' => 'verification_failed'];
        }

        // 8. ثبت نتیجه موفق
        $order->status = 'paid';
        $order->ref_code = $result['ref_id'];
        $order->save();

        return [
            'type' => 'success',
            'url' => $result['url'] ?? '/cart',
        ];
    }, 3);

    // عملیات جانبی پس از ثبت تراکنش دیتابیس
    if ($outcome['type'] === 'already_paid') {
        session()->forget('current_order_id');

        $this->redirect('/cart')
            ->flashSuccess('این سفارش قبلاً پرداخت شده است.');

        return;
    }

    if ($outcome['type'] === 'cancelled') {
        session()->forget('current_order_id');

        $this->redirect('/cart')
            ->flashError('عملیات پرداخت لغو شد.');

        return;
    }

    if ($outcome['type'] === 'verification_failed') {
        $this->redirect('/cart')
            ->flashError('تأیید پرداخت ناموفق بود.');

        return;
    }

    if ($outcome['type'] === 'success') {
        // توجه: فقط وقتی سبد فعلی متعلق به همین سفارش است حذفش کن.
        $this->cart->items()->delete();
        $this->cart->delete();

        session()->forget('current_order_id');

        $this->redirect($outcome['url'])
            ->flashSuccess('پرداخت با موفقیت انجام شد.');
    }
}

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.cart');
    }
}
