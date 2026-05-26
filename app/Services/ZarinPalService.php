<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Session;

class ZarinPalService
{
    protected $merchant_id;
    protected $baseUrl = 'https://sandbox.banktest.ir/zarinpal/api.zarinpal.com/pg/v4/payment/';

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->merchant_id = '68B14720-8788-42D5-9A9D-C1E1033715C5';
    }

    public function request($amount, $callbackUrl, $metadata = [])
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/request.json}", [
            'merchant_id' => $this->merchant_id,
            'amount' => $amount,
            'callback_url' => $callbackUrl,
            'metadata' => $metadata,
            'currency' => 'IRR',
            'description' => 'پرداخت سفارش'
        ]);
        $data = $response->json();

        if ($response['data']['status'] == 100) {
            return [
                'success' => true,
                'authority' => $response['data']['Authority'],
                'ref_code' => null
            ];
        }

        return [
            'success' => false,
            'message' => $data['errors']['code'] ?? 'خطای ناشناخته'
        ];
    }

    public function verify( $orderId,$authority, $status){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/verify.json", [
            'order_id' => $this->merchant_id,
            'authority' => $authority,
            'status' => $status,
        ]);

        if ($status == 'OK') {
            // پرداخت موفق
            if (!$authority) {
                return redirect()->route('cart')->with('error', 'اطلاعات پرداخت یافت نشد.');
            }
//            $data = array("merchant_id" => "68B14720-8788-42D5-9A9D-C1E1033715C5", "Authority" => $authority , 'Status'=> $status);
//            $result = json_encode($data);
            $order = \App\Models\Order::findOrFail($orderId);

            dd($response['data']);
            if ($result['data']['code'] == 100) {
                // پرداخت نهایی تایید شد
                $order->update([
                    'status' => 'paid',
                    'ref_code' => $result['data']['ref_code'],
                ]);
                // خالی کردن سبد خرید
                session()->forget('cart');
                $this->cart->items->delete();
                $this->cart->delete();
                $this->status = 'success';
            } else {
                // خطا در تایید نهایی (مثلاً موجودی کافی نبوده ولی کاربر پرداخت کرده بود؟ نادر است اما ممکن)
                $order->update([
                    'status' => 'failed_verify',
                ]);
                $this->status = 'error';
                session()->flash('message', 'تاییدیه پرداخت با مشکل مواجه شد. کد خطا: ' . $data['status_message']);
            }
        } else {
            // پرداخت لغو شده توسط کاربر
            \App\Models\Order::where('id', $orderId)->update([
                'status' => 'cancelled',
            ]);
            $this->status = 'cancelled';
        }
    }

    private function processOrderItem($orderId)
    {
        $cartItems = session()->get('cart',[]);

        foreach ($cartItems as $item) {
            $product = Product::query()->find($item->product->id);
            if ($product){
                OrderItem::query()->create([
                    'order_id' => $orderId,
                    'product_variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                ]);
            }
        }
        \session()->forget('cart');
    }

}
