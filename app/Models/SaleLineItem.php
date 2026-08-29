<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_transaction_id',
        'product_variant_id',
        'quantity',
        'unit_price_at_sale',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price_at_sale' => 'decimal:2',
    ];

    public function salesTransaction(): BelongsTo
    {
        return $this->belongsTo(SalesTransaction::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->unit_price_at_sale;
    }
}
