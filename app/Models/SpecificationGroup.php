<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperSpecificationGroup
 */
class SpecificationGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relationships
     public function specifications()
    {
        return $this->belongsToMany(
            Specification::class,
            'spec_group_specs',
            'spec_group_id',
            'specification_id'
        )
        ->withPivot('sort_order')
        ->orderBy('spec_group_specs.sort_order');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_spec_groups', 'spec_group_id', 'category_id')
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    // Accessor for specifications_count
    public function getSpecificationsCountAttribute()
    {
        if (array_key_exists('specifications_count', $this->attributes)) {
            return $this->attributes['specifications_count'];
        }

        return $this->specifications()->count();
    }

    // Scope for active groups
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
