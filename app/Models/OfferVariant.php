<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperOfferVariant
 */
class OfferVariant extends Model
{
    protected $fillable = [
        'offer_id',
        'product_variant_id',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
