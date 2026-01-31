<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCart
 */
class Cart extends Model
{
    protected $fillable = [
        'customer_id',
        'session_id',
        'currency_id',
        'status',
        'subtotal',
        'tax_total',
        'shipping_total',
        'discount_total',
        'grand_total',
        'offer_id',
        'shipping_address_id',
        'billing_address_id',
        'abandoned_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'shipping_total' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'abandoned_at' => 'datetime',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(CustomerAddress::class, 'shipping_address_id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(CustomerAddress::class, 'billing_address_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function activeItems()
    {
        return $this->items()->whereHas('variant', function ($query) {
            $query->where('status', true);
        });
    }

    public function getTaxBreakdownAttribute()
    {
        $breakdown = [];
        $this->loadMissing('items.variant.product.taxClass.rates');

        foreach ($this->items as $item) {
            // Skip items with missing variants
            if (!$item->variant || !$item->variant->product) {
                continue;
            }
            
            $product = $item->variant->product;
            if ($product && $product->taxClass) {
                foreach ($product->taxClass->rates as $rate) {
                    if ($rate->is_active) {
                        $amount = $item->total * ($rate->rate / 100);
                        $label = $rate->name;
                        
                        if (!isset($breakdown[$label])) {
                            $breakdown[$label] = [
                                'name' => $label,
                                'rate' => (float)$rate->rate,
                                'amount' => 0
                            ];
                        }
                        $breakdown[$label]['amount'] += $amount;
                    }
                }
            }
        }
        
        return array_values($breakdown);
    }
}