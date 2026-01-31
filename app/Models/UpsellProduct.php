<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperUpsellProduct
 */
class UpsellProduct extends Model
{
    protected $fillable = [
        'product_id',
        'upsell_product_id',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function upsellProduct()
    {
        return $this->belongsTo(Product::class, 'upsell_product_id');
    }
}