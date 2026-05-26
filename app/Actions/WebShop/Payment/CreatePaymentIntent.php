<?php

namespace App\Actions\WebShop\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;

class CreatePaymentIntent
{
    public function execute(int $amount, string $callbackUrl, string $merchantId, string $email, $mobile): array
    {
        $baseUrl = 'https://sandbox.banktest.ir/zarinpal/api.zarinpal.com/pg/v4/payment/';
        try {
            $response = Http::post($baseUrl . 'request.json', [
                'merchant_id' => $merchantId,
                'amount' => $amount,
                'callback_url' => $callbackUrl,
                'metadata' => [
                    'mobile' => $mobile,
                    'email' => $email,
                ]
            ]);

            if (isset($response['data']['code']) && $response['data']['code'] == 100) {
                return [
                    'success' => true,
                    'url' => "https://sandbox.banktest.ir/zarinpal/www.zarinpal.com/pg/StartPay/" . $response['data']['authority'],
                ];
            }
            return ['success' => false, 'message' => $response['errors']['message'] ?? 'خطا'];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
