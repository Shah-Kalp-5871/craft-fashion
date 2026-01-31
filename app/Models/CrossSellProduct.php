<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCrossSellProduct
 */
class CrossSellProduct extends Model
{
    protected $fillable = [
        'product_id',
        'cross_sell_product_id',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function crossSellProduct()
    {
        return $this->belongsTo(Product::class, 'cross_sell_product_id');
    }
}