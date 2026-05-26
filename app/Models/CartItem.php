<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class CartItem extends Model
{
    /** @use HasFactory<\Database\Factories\CartItemFactory> */
    use HasFactory;
    protected $fillable = ['cart_id','quantity','product_variant_id'];
    public function subtotal(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->product->price * $this->quantity;
            });
    }

    public function product(): HasOneThrough
    {
        return $this->hasOneThrough(Product::class, ProductVariant::class,
            'id',
            'id',
            'product_variant_id',
            'product_id',
        );
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
