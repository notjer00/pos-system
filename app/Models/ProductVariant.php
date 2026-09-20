<?php

namespace App\Models;

use Database\Factories\ProductVariantFactory;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected static function newFactory(): ProductVariantFactory
    {
        return ProductVariantFactory::new();
    }

    protected $fillable = [
        'product_id',
        'variant_name',
        'sku',
        'size',
        'footwear_size',
        'color',
        'current_stock',
        'low_stock_threshold',
    ];

    protected $casts = [
        'current_stock' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function saleLineItems(): HasMany
    {
        return $this->hasMany(SaleLineItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->low_stock_threshold;
    }

    public function decrementStock(int $quantity): bool
    {
        return $this->decrement('current_stock', $quantity);
    }

    public function getEffectiveSize(): string
    {
        return $this->variant_name ?? $this->footwear_size ?? $this->size ?? '';
    }

    public function getVariantDisplayName(): string
    {
        return $this->variant_name ?? $this->getEffectiveSize();
    }

    public function generateSku(): string
    {
        $productCode = strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', $this->product->name), 0, 6));
        $variantCode = strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', $this->variant_name ?? ''), 0, 6));

        if (empty($variantCode)) {
            $colorCode = strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', $this->color ?? ''), 0, 3));
            $sizeValue = $this->getEffectiveSize();
            $variantCode = trim("{$colorCode}-{$sizeValue}", '-');
        }

        $baseSku = sprintf('%s-%s', $productCode, $variantCode);

        $counter = 1;
        $sku = $baseSku;
        while (static::where('sku', $sku)->exists()) {
            $sku = $baseSku.'-'.$counter;
            $counter++;
        }

        return $sku;
    }

    public function getQrCodeSvg(): string
    {
        $qrCode = new QrCode($this->sku);
        $writer = new SvgWriter;
        $result = $writer->write($qrCode);

        return $result->getString();
    }
}
