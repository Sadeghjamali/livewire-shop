<?php

namespace App\Actions\WebShop\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class VerifyPayment
{
    public function execute(string $authority, int $amount, string $status): array
    {
        try {
            $response = Http::withHeaders(['Content-Type'=>'application/json','Accept'=>'application/json'])
            ->post('https://sandbox.banktest.ir/zarinpal/api.zarinpal.com/pg/v4/payment/verify.json', [
                'merchant_id' => '68B14720-8788-42D5-9A9D-C1E1033715C5',
                'authority' => $authority,
                'amount' => $amount,
                'status' => $status,
            ]);

            if ($response['data']['code'] === 100 && isset($response['data']['ref_id'])) {
                return [
                    'success' => true,
                    'ref_id' => $response['data']['ref_id'],
                    'url' => route('cart'),
                ];
            }
            return ['success' => false, 'message' => $response['errors']['message'] ?? 'پرداخت ناموفق'];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
