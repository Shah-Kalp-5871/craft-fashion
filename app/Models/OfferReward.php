<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperOfferReward
 */
class OfferReward extends Model
{
    protected $fillable = [
        'offer_id',
        'reward_product_id',
        'reward_variant_id',
        'reward_qty',
        'same_as_buy_product',
    ];

    protected $casts = [
        'same_as_buy_product' => 'boolean',
    ];

    // Relationships
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'reward_product_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'reward_variant_id');
    }
}