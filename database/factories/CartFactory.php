<?php

namespace Database\Factories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Session;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public static function make()
    {
        return match (auth()->guest()){
            true => Cart::query()->firstOrCreate(['session_id' => Session::getId()]),
            false =>auth()->user()->cart ?: auth()->user()->cart()->create()
        };
    }
}
