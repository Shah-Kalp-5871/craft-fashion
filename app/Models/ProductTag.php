<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperProductTag
 */
class ProductTag extends Pivot
{
    protected $table = 'product_tags';

    protected $fillable = [
        'product_id',
        'tag_id',
    ];

    public $incrementing = false;
    public $timestamps = true;
}