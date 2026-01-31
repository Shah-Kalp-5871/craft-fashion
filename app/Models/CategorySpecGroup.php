<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCategorySpecGroup
 */
class CategorySpecGroup extends Pivot  // Extend Pivot, not Model
{
    protected $table = 'category_spec_groups';

    protected $fillable = [
        'category_id',
        'spec_group_id',
        'sort_order',
    ];

    // Since Pivot models don't have timestamps by default,
    // we need to enable them if your table has them
    public $timestamps = true;

    // Add increments to use auto-incrementing IDs (if your table has id column)
    public $incrementing = true;

    // Relationships still work in pivot models
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function specificationGroup(): BelongsTo
    {
        return $this->belongsTo(SpecificationGroup::class, 'spec_group_id');
    }
}
