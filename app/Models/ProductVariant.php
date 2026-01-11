<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'old_price',
        'stock',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
    ];

    /**
     * Get the product that owns the variant.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the attribute values for the variant.
     */
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'variant_attribute_values', 'product_variant_id', 'attribute_value_id');
    }

    /**
     * Get the cart items for the product variant.
     */
    public function cartItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Kiểm tra xem có đang giảm giá không
     */
    public function hasDiscount(): bool
    {
        return $this->old_price && $this->old_price > $this->price;
    }

    /**
     * Tính phần trăm giảm giá
     */
    public function getDiscountPercent(): float
    {
        if (!$this->hasDiscount()) {
            return 0;
        }
        return round((($this->old_price - $this->price) / $this->old_price) * 100, 0);
    }

    /**
     * Tính số tiền giảm
     */
    public function getDiscountAmount(): float
    {
        if (!$this->hasDiscount()) {
            return 0;
        }
        return $this->old_price - $this->price;
    }
}
