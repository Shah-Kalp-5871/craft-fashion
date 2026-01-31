<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCustomerSegment
 */
class CustomerSegment extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'conditions',
        'customer_count',
        'is_active',
    ];

    protected $casts = [
        'conditions' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_segment_members');
    }

    public function members(): HasMany
    {
        return $this->hasMany(CustomerSegmentMember::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function tierPrices(): HasMany
    {
        return $this->hasMany(TierPrice::class);
    }
}