<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperReviewVote
 */
class ReviewVote extends Model
{
    protected $fillable = [
        'product_review_id',
        'customer_id',
        'session_id',
        'vote',
    ];

    // Relationships
    public function review(): BelongsTo
    {
        return $this->belongsTo(ProductReview::class, 'product_review_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}