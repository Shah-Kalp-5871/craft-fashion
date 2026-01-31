<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCustomerAddress
 */
class CustomerAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'type',
        'name',
        'mobile',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'latitude',
        'longitude',
        'is_default',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_default' => 'boolean',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function shippingCarts()
    {
        return $this->hasMany(Cart::class, 'shipping_address_id');
    }

    public function billingCarts()
    {
        return $this->hasMany(Cart::class, 'billing_address_id');
    }
}