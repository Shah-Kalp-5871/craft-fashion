<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperCategoryProduct
 */
class CategoryProduct extends Pivot
{
    protected $table = 'category_product';

    protected $fillable = [
        'product_id',
        'category_id',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public $incrementing = false;
    public $timestamps = true;
}   