<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperInventoryTransferItem
 */
class InventoryTransferItem extends Model
{
    protected $fillable = [
        'inventory_transfer_id',
        'product_variant_id',
        'quantity',
        'received_quantity',
    ];

    // Relationships
    public function transfer(): BelongsTo
    {
        return $this->belongsTo(InventoryTransfer::class, 'inventory_transfer_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}