<?php

namespace App\Livewire;


use Database\Factories\CartFactory;
use Livewire\Component;

class NavigationCart extends Component
{
    protected $listeners = [
        'productAddToCart' => '$refresh',
        'productRemoveItemCart' => '$refresh',
        'incrementItemCart' => '$refresh',
        'decrementItemCart' => '$refresh',
        'productVerifyCart' => '$refresh',
        'paymentSuccess' => '$refresh',
    ];

    /**
     * @return int
     */
    public function getCountProperty(): int
    {
        return CartFactory::make()->items()->sum('quantity');
    }


    public function render()
    {
        return view('livewire.navigation-cart');
    }
}
