<?php

namespace App\Actions\WebShop;

use App\Models\Cart;
use Database\Factories\CartFactory;
use Illuminate\Support\Facades\Session;

class AddProductVariantToCart
{
    public function add($variantId , $quantity = 1 ,$cart = null)
    {
        ($cart ?:CartFactory::make())->items()->firstOrCreate(
            ['product_variant_id' => $variantId],
            ['quantity' => 0]
        )->increment('quantity', $quantity);
    }
}
