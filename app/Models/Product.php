<?php

namespace App\Models;

use Brick\Money\Context\CustomContext;
use Brick\Money\Currency;
use Brick\Money\Formatter\MoneyNumberFormatter;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use NumberFormatter;


class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $table = 'products';

    protected function price():Attribute
    {
        return Attribute::make(
            get: function (int $price) {
                return $price;
            }
        );
    }

    public function variants(): HasMany{
        return $this->hasMany(ProductVariant::class);
    }

    public function image()
    {
        return $this->hasOne(Image::class)->ofMany('featured','max');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function orderItems():HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

}
