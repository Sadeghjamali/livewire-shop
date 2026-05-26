<?php

namespace App\Livewire;


use App\Actions\WebShop\Payment\CreatePaymentIntent;
use App\Actions\WebShop\Payment\VerifyPayment;
use App\Models\Order;
use Database\Factories\CartFactory;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Attributes\Layout;
use Livewire\Component;


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
        $this->cart->items()->find($itemId)->increment('quantity');
        $this->dispatch('incrementItemCart');
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
        if($order){
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

    public function verify(VerifyPayment $action, $order_id, $authority, $status): void
    {
        // اگر وضعیت Success باشد، اکشن Verify را اجرا کن
        $pendingData = session('current_order_id');
        if ($pendingData) {
            $result = $action->execute(
                authority: $authority,
                amount: $this->total_amount,
                status: $status,
            );

            $order = Order::query()->find($order_id);
            if ($order->status !== 'processing') {
                abort(403, 'وضعیت سفارش نامعتبر');
            }

            if ($result['success']) {
                $order->status = 'paid';
                $order->ref_code = $result['ref_id'];
                $order->save();

                $this->cart->items()->delete();
                $this->cart->delete();

                session()->forget('current_order_id');
                $this->redirect($result['url'])->flashSuccess('پرداخت با موفقیت انجام شد');
            } elseif ($status === 'Cancel') {
                $order->status = 'failed';
                $order->save();
                session()->forget('current_order_id');
                $this->redirect('/cart')->flashError('عملیات پرداخت کنسل شد');
            }
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.cart');
    }
}
