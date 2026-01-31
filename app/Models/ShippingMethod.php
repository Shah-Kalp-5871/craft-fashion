<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperShippingMethod
 */
class ShippingMethod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'config',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
    ];

    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function shippingCharges(): HasMany
    {
        return $this->hasMany(ShippingCharge::class);
    }

    public function shippingZones(): BelongsToMany
    {
        return $this->belongsToMany(ShippingZone::class, 'shipping_charges')
            ->withPivot('min_weight', 'max_weight', 'min_price', 'max_price', 'charge', 'free_shipping_threshold', 'is_active')
            ->withTimestamps();
    }
}