<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperGiftCard
 */
class GiftCard extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'initial_value',
        'current_value',
        'currency_id',
        'purchased_by',
        'recipient_id',
        'recipient_email',
        'recipient_name',
        'message',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'initial_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    // Relationships
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function purchaser(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'purchased_by');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'recipient_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(GiftCardTransaction::class);
    }
}