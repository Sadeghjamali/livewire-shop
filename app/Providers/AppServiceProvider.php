<?php

namespace App\Providers;

use App\Actions\WebShop\MigrateSessionCart;
use App\Models\User;
use Brick\Money\Money;
use Database\Factories\CartFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                (new MigrateSessionCart())->migrate(
                    CartFactory::make() ,
                    $user?->cart ?: $user->cart->create());
                return $user;
            }
        });
    }
}
