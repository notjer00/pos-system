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
        'description',
        'is_active',
        'gender',
        'size_system',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'gender' => 'string',
        'size_system' => 'string',
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
        // First check for product-specific active discount
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
            ->first();

        if ($productDiscount) {
            return $productDiscount;
        }

        // Fall back to store-wide active discount (product_id IS NULL)
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

    public function getSizeOptions(): array
    {
        return match ($this->size_system) {
            'us_footwear' => [4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 11.5, 12, 13, 14],
            'eu_footwear' => range(35, 48),
            'uk_footwear' => [3, 3.5, 4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 11.5, 12, 13],
            default => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
        };
    }
}
