<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperShippingCharge
 */
class ShippingCharge extends Model
{
    protected $fillable = [
        'shipping_zone_id',
        'shipping_method_id',
        'min_weight',
        'max_weight',
        'min_price',
        'max_price',
        'charge',
        'free_shipping_threshold',
        'is_active',
    ];

    protected $casts = [
        'min_weight' => 'decimal:3',
        'max_weight' => 'decimal:3',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'charge' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
}