<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperReturns
 */
class Returns extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'return_number',
        'order_id',
        'customer_id',
        'status',
        'type',
        'reason',
        'notes',
        'refund_amount',
        'refund_payment_id',
        'requested_at',
        'approved_at',
        'received_at',
        'processed_at',
        'completed_at',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'received_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function refundPayment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'refund_payment_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReturnItem::class);
    }
}