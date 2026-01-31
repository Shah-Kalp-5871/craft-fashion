<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperOrder
 */
class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'payment_method',
        'shipping_method',
        'currency',
        'status',
        'payment_status',
        'shipping_status',
        'subtotal',
        'tax_total',
        'shipping_total',
        'discount_total',
        'grand_total',
        'offer_id',
        'loyalty_points_used',
        'loyalty_points_earned',
        'shipping_address',
        'billing_address',
        'customer_notes',
        'admin_notes',
        'cancellation_reason',
        'cancelled_at',
        'confirmed_at',
        'processing_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'shipping_total' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'loyalty_points_used' => 'decimal:2',
        'loyalty_points_earned' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'cancelled_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'processing_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }



    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentAttempts(): HasMany
    {
        return $this->hasMany(PaymentAttempt::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(Returns::class);
    }

    public function offerUsages(): HasMany
    {
        return $this->hasMany(OfferUsage::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latest();
    }

    public function latestShipment(): HasOne
    {
        return $this->hasOne(Shipment::class)->latest();
    }
}
