<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperProductSpecification
 */
class ProductSpecification extends Model
{
    protected $fillable = [
        'product_id',
        'specification_id',
        'specification_value_id',
        'custom_value',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class);
    }

    public function specificationValue(): BelongsTo
    {
        return $this->belongsTo(SpecificationValue::class, 'specification_value_id');
    }

    // Accessor to get the display value
    public function getDisplayValueAttribute()
    {
        if ($this->custom_value) {
            return $this->custom_value;
        }

        if ($this->specificationValue) {
            return $this->specificationValue->value;
        }

        return null;
    }
}
