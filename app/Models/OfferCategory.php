<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperOfferCategory
 */
class OfferCategory extends Model
{
    protected $fillable = [
        'offer_id',
        'category_id',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}