<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperLoyaltyTransaction
 */
class LoyaltyTransaction extends Model
{
    protected $fillable = [
        'customer_loyalty_id',
        'type',
        'points',
        'balance',
        'reference_type',
        'reference_id',
        'notes',
    ];

    protected $casts = [
        'points' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    // Relationships
    public function customerLoyalty(): BelongsTo
    {
        return $this->belongsTo(CustomerLoyalty::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }
}