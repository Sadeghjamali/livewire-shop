<?php

namespace App\Livewire;

use App\Actions\WebShop\AddProductVariantToCart;
use JetBrains\PhpStorm\NoReturn;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Product extends Component
{
    use InteractsWithBanner;
//    public $productId;
    public \App\Models\Product $product;
    #[Validate(['required','exists:App\Models\ProductVariant,id'])]
    public $variant;

    public function mount(): void
    {
        /*$redis = new \Predis\Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
        ]);
        $redis->zincrby('trend_products', 1 ,$this->product);*/
//        $redis->zremrangebyrank('trend_products',0,-5);
        $this->variant = $this->product->variants()->value('id');
    }

    public function addToCart(AddProductVariantToCart $cart)
    {
        $this->validate();
        $cart->add(
            variantId:$this->variant
        );
        $this->banner('Product added to cart');
        $this->dispatch('productAddToCart');
    }

    #[Computed]
    public function Product()
    {
        return $this->product->with('image','images','variants')->get();
    }
    #[Layout('layouts.app')]
    public function render()
    {
//        dd($product);
        return view('livewire.product');
    }
}
