<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'type',
        'input_type',
        'data_type',
        'unit',
        'description',
        'is_required',
        'is_filterable',
        'sort_order',
        'status'
    ];

    // Relationship with specification groups
    public function specificationGroups()
    {
        return $this->belongsToMany(
            SpecificationGroup::class,
            'spec_group_specs',
            'specification_id',
            'spec_group_id'
        )->withPivot('sort_order')->withTimestamps();
    }

    // Relationship with categories
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_specifications', // Make sure this table exists
            'specification_id',
            'category_id'
        )->withTimestamps();
    }

    // Relationship with specification values
     public function values()
    {
        return $this->hasMany(SpecificationValue::class)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('value');
    }

    // Relationship with products
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_specifications',
            'specification_id',
            'product_id'
        )->withPivot(['specification_value_id', 'custom_value'])->withTimestamps();
    }

    // Scope for active specifications
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
