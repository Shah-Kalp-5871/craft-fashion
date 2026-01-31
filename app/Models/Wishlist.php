<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperWishlist
 */
class Wishlist extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function products()
    {
        return $this->hasManyThrough(
            ProductVariant::class,
            WishlistItem::class,
            'wishlist_id',
            'id',
            'id',
            'product_variant_id'
        );
    }
}
