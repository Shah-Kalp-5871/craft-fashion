<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperShipmentItem
 */
class ShipmentItem extends Model
{
    protected $fillable = [
        'shipment_id',
        'order_item_id',
        'quantity',
    ];

    // Relationships
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
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