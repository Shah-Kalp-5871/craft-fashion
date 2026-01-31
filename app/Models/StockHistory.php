<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperStockHistory
 */
class StockHistory extends Model
{
    protected $table = 'stock_history';

    protected $fillable = [
        'product_variant_id',
        'change_type',
        'quantity',
        'old_quantity',
        'new_quantity',
        'reason',
        'source_type',
        'source_id',
        'admin_id',
        'customer_id',
        'notes',
    ];

    // Relationships
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }
}
