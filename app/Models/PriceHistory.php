<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @mixin IdeHelperPriceHistory
 */
class PriceHistory extends Model
{
    protected $fillable = [
        'product_variant_id',
        'old_price',
        'new_price',
        'old_compare_price',
        'new_compare_price',
        'changed_by',
        'change_reason',
        'effective_from',
        'effective_to',
    ];

    protected $casts = [
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'old_compare_price' => 'decimal:2',
        'new_compare_price' => 'decimal:2',
        'effective_from' => 'datetime',
        'effective_to' => 'datetime',
    ];

    // Relationships
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'changed_by');
    }
}