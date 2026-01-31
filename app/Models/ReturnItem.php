<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperReturnItem
 */
class ReturnItem extends Model
{
    protected $fillable = [
        'return_id',
        'order_item_id',
        'quantity',
        'condition',
        'reason',
        'refund_amount',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
    ];

    // Relationships
    public function return(): BelongsTo
    {
        return $this->belongsTo(Returns::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function variant()
    {
        return $this->hasOneThrough(
            ProductVariant::class,
            OrderItem::class,
            'id',
            'id',
            'order_item_id',
            'product_variant_id'
        );
    }
}