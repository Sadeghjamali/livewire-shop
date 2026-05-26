<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class FrontStore extends Component
{
    #[Computed]
    public function Products()
    {
//        Cache::forget('products');
        return Cache::remember('products', 60*60, function () {
            return Product::query()->get();
        });
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.front-store');
    }
}
