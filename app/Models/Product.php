<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    protected $fillable = [
        'name',
        'base_price',
        'category',
        'brand',
        'unit',
        'description',
        'is_active',
        'gender',
        'size_system',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    public function activeDiscount(): ?Discount
    {
        $productDiscount = $this->discounts()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->orderByDesc('percentage')
            ->orderByDesc('created_at')
            ->first();

        if ($productDiscount) {
            return $productDiscount;
        }

        return Discount::query()
            ->where('is_active', true)
            ->whereNull('product_id')
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->orderByDesc('percentage')
            ->orderByDesc('created_at')
            ->first();
    }

    public function getCurrentPriceAttribute(): float
    {
        $discount = $this->activeDiscount();
        if ($discount) {
            return $this->base_price * (1 - $discount->percentage / 100);
        }

        return $this->base_price;
    }
}
