<?php

namespace App\Models;

use Database\Factories\CommissionLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionLog extends Model
{
    use HasFactory;

    protected static function newFactory(): CommissionLogFactory
    {
        return CommissionLogFactory::new();
    }

    protected $fillable = [
        'sales_transaction_id',
        'user_id',
        'base_amount',
        'discount_applied',
        'final_price',
        'commission_rate',
        'commission_earned',
        'is_voided',
    ];

    protected $casts = [
        'base_amount' => 'decimal:2',
        'discount_applied' => 'decimal:2',
        'final_price' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_earned' => 'decimal:2',
        'is_voided' => 'boolean',
    ];

    public function salesTransaction(): BelongsTo
    {
        return $this->belongsTo(SalesTransaction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
