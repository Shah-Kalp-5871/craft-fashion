<?php
// app/Models/Customer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCustomer
 */
class Customer extends Authenticatable
{
    use SoftDeletes, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'status',
        'is_block',
        'blocked_at',
        'block_reason',
        'blocked_by',
        'email_verified_at',
        'mobile_verified_at',
        'password_changed_at',
        'last_login_at',
        'last_login_ip',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_block' => 'boolean',
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'last_login_at' => 'datetime',
        'blocked_at' => 'datetime',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true)->where('is_block', false);
    }

    public function scopeBlocked($query)
    {
        return $query->where('is_block', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }

    // Relationships
    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(Returns::class);
    }

    public function productReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function reviewVotes(): HasMany
    {
        return $this->hasMany(ReviewVote::class);
    }

    public function customerLoyalty(): HasMany
    {
        return $this->hasMany(CustomerLoyalty::class);
    }

    public function segments(): BelongsToMany
    {
        return $this->belongsToMany(CustomerSegment::class, 'customer_segment_members');
    }

    public function passwordHistories(): HasMany
    {
        return $this->hasMany(PasswordHistory::class, 'user_id')->where('user_type', self::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function auditTrails(): HasMany
    {
        return $this->hasMany(AuditTrail::class);
    }

    public function giftCards(): HasMany
    {
        return $this->hasMany(GiftCard::class, 'purchased_by');
    }

    public function receivedGiftCards(): HasMany
    {
        return $this->hasMany(GiftCard::class, 'recipient_id');
    }

    public function shippingAddress(): HasOne
    {
        return $this->hasOne(CustomerAddress::class)->where('type', 'shipping')->where('is_default', true);
    }

    public function billingAddress(): HasOne
    {
        return $this->hasOne(CustomerAddress::class)->where('type', 'billing')->where('is_default', true);
    }

    public function blocker(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'blocked_by');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        if ($this->is_block) {
            return '<span class="badge bg-danger">Blocked</span>';
        }

        return $this->status
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-warning">Inactive</span>';
    }




    public function getOrdersCountAttribute()
    {
        return $this->orders()->count();
    }

    public function getLastLoginFormattedAttribute()
    {
        return $this->last_login_at
            ? $this->last_login_at->format('Y-m-d H:i')
            : 'Never';
    }
}
