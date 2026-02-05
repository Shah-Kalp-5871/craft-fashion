<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperPayment
 */
class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_method',
        'transaction_id',
        'gateway_order_id',
        'amount',
        'currency',
        'payment_gateway',
        'status',
        'payment_details',
        'request_data',
        'response_data',
        'gateway_response',
        'failure_reason',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'request_data' => 'array',
        'response_data' => 'array',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(Returns::class, 'refund_payment_id');
    }
}