<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperShipment
 */
class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'tracking_number',
        'carrier',
        'carrier_service',
        'status',
        'weight',
        'dimensions',
        'shipping_label',
        'shipped_at',
        'estimated_delivery',
        'delivered_at',
        'delivery_notes',
        'delivered_to',
    ];

    protected $casts = [
        'weight' => 'decimal:3',
        'dimensions' => 'array',
        'shipping_label' => 'array',
        'shipped_at' => 'datetime',
        'estimated_delivery' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }
}