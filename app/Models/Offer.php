<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperOffer
 */
class Offer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'status',
        'offer_type',
        'discount_value',
        'buy_qty',
        'get_qty',
        'min_cart_amount',
        'max_cart_amount',
        'max_discount',
        'max_uses',
        'uses_per_customer',
        'used_count',
        'starts_at',
        'ends_at',
        'is_auto_apply',
        'is_stackable',
        'is_exclusive',
        'customer_segment_id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'discount_value' => 'decimal:2',
        'min_cart_amount' => 'decimal:2',
        'max_cart_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_auto_apply' => 'boolean',
        'is_stackable' => 'boolean',
        'is_exclusive' => 'boolean',
    ];

    // Relationships
    public function customerSegment(): BelongsTo
    {
        return $this->belongsTo(CustomerSegment::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(OfferUsage::class);
    }

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(ProductVariant::class, 'offer_variants');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'offer_categories');
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(OfferReward::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true)
                    ->where(function($q) {
                        $q->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('ends_at')
                          ->orWhere('ends_at', '>=', now());
                    });
    }

    public function scopeExpired($query)
    {
        return $query->where('ends_at', '<', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>', now());
    }

    public function scopeAutoApply($query)
    {
        return $query->where('is_auto_apply', true);
    }

    // Helper methods
    public function isActive(): bool
    {
        if (!$this->status) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }

        if ($this->ends_at && $this->ends_at < $now) {
            return false;
        }

        return true;
    }

    public function isExpired(): bool
    {
        return $this->ends_at && $this->ends_at < now();
    }

    public function isUpcoming(): bool
    {
        return $this->starts_at && $this->starts_at > now();
    }

    public function getDaysRemaining(): int
    {
        if (!$this->ends_at || $this->ends_at < now()) {
            return 0;
        }

        return now()->diffInDays($this->ends_at, false);
    }

    public function getOfferTypeText(): string
    {
        $types = [
            'percentage' => 'Percentage Discount',
            'fixed' => 'Fixed Amount',
            'bogo' => 'Buy One Get One',
            'buy_x_get_y' => 'Buy X Get Y',
            'free_shipping' => 'Free Shipping',
            'tiered' => 'Tiered Discount'
        ];

        return $types[$this->offer_type] ?? $this->offer_type;
    }

    public function canApply($customerId = null): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        // Check max uses
        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        // Check per customer limit
        if ($customerId && $this->uses_per_customer) {
            $customerUsage = $this->usages()
                ->where('customer_id', $customerId)
                ->count();

            if ($customerUsage >= $this->uses_per_customer) {
                return false;
            }
        }

        return true;
    }

    public function incrementUsage($customerId = null, $orderId = null, $discountAmount = 0): void
    {
        $this->increment('used_count');

        // Record usage
        if ($customerId || $orderId) {
            OfferUsage::create([
                'offer_id' => $this->id,
                'customer_id' => $customerId,
                'order_id' => $orderId,
                'discount_amount' => $discountAmount,
                'used_at' => now()
            ]);
        }
    }
}
