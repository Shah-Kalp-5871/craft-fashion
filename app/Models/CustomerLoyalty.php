<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCustomerLoyalty
 */
class CustomerLoyalty extends Model
{
    protected $fillable = [
        'customer_id',
        'loyalty_program_id',
        'total_points',
        'available_points',
        'used_points',
        'expired_points',
        'tier_level',
    ];

    protected $casts = [
        'total_points' => 'decimal:2',
        'available_points' => 'decimal:2',
        'used_points' => 'decimal:2',
        'expired_points' => 'decimal:2',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(LoyaltyProgram::class, 'loyalty_program_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }
}