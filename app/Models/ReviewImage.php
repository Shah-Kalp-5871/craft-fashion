<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperReviewImage
 */
class ReviewImage extends Model
{
    protected $fillable = [
        'product_review_id',
        'media_id',
        'sort_order',
    ];

    // Relationships
    public function review(): BelongsTo
    {
        return $this->belongsTo(ProductReview::class, 'product_review_id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}