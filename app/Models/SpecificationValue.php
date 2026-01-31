<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperSpecificationValue
 */
class SpecificationValue extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'specification_id',
        'value',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relationships
    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class);
    }

    public function productSpecifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class, 'specification_value_id');
    }

    // Scope for active values
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Scope for ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('value');
    }
}
