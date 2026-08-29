<?php

namespace App\Models;

use Database\Factories\StockMovementFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected static function newFactory(): StockMovementFactory
    {
        return StockMovementFactory::new();
    }

    protected $fillable = [
        'product_variant_id',
        'type',
        'quantity_change',
        'note',
        'created_by',
    ];

    protected $casts = [
        'quantity_change' => 'integer',
    ];

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isSale(): bool
    {
        return $this->type === 'sale';
    }

    public function isRestock(): bool
    {
        return $this->type === 'restock';
    }

    public function isVoidReversal(): bool
    {
        return $this->type === 'void_reversal';
    }

    public function isRefundReversal(): bool
    {
        return $this->type === 'refund_reversal';
    }
}
