<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperShippingZone
 */
class ShippingZone extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'countries',
        'states',
        'zip_codes',
        'is_active',
    ];

    protected $casts = [
        'countries' => 'array',
        'states' => 'array',
        'zip_codes' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function shippingCharges(): HasMany
    {
        return $this->hasMany(ShippingCharge::class);
    }

    public function shippingMethods(): BelongsToMany
    {
        return $this->belongsToMany(ShippingMethod::class, 'shipping_charges')
            ->withPivot('min_weight', 'max_weight', 'min_price', 'max_price', 'charge', 'free_shipping_threshold', 'is_active')
            ->withTimestamps();
    }
}