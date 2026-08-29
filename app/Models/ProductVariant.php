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
        return $this->footwear_size ?? $this->size;
    }

    public function generateSku(): string
    {
        $productCode = strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', $this->product->name), 0, 6));
        $colorCode = strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', $this->color), 0, 3));
        $genderCode = strtoupper(substr($this->product->gender, 0, 1));
        $sizeValue = $this->getEffectiveSize();

        $baseSku = sprintf('%s-%s-%s-%s', $productCode, $colorCode, $genderCode, $sizeValue);

        // Ensure uniqueness by appending a counter if needed
        $counter = 1;
        $sku = $baseSku;
        while (static::where('sku', $sku)->where('product_id', $this->product_id)->exists()) {
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
