<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperLoyaltyProgram
 */
class LoyaltyProgram extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'points_per_currency',
        'signup_bonus',
        'first_purchase_bonus',
        'min_redeemable_points',
        'point_value',
        'starts_at',
        'ends_at',
        'status',
    ];

    protected $casts = [
        'points_per_currency' => 'decimal:2',
        'min_redeemable_points' => 'decimal:2',
        'point_value' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'status' => 'boolean',
    ];

    // Relationships
    public function customerLoyalties(): HasMany
    {
        return $this->hasMany(CustomerLoyalty::class);
    }
}