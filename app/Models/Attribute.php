<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperAttribute
 */
class Attribute extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'type',
        'is_variant',
        'is_filterable',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'is_variant' => 'boolean',
        'is_filterable' => 'boolean',
        'status' => 'boolean',
    ];

    // Relationships
     public function values()
    {
        return $this->hasMany(AttributeValue::class)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('label');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_attributes')
            ->withPivot('is_required', 'is_filterable', 'sort_order')
            ->withTimestamps();
    }

    public function variantAttributes(): HasMany
    {
        return $this->hasMany(VariantAttribute::class);
    }
}
