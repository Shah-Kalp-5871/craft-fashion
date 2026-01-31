<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCategoryHierarchy
 */
class CategoryHierarchy extends Model
{
    protected $fillable = [
        'ancestor_id',
        'descendant_id',
        'depth',
    ];

    public function ancestor()
    {
        return $this->belongsTo(Category::class, 'ancestor_id');
    }

    public function descendant()
    {
        return $this->belongsTo(Category::class, 'descendant_id');
    }
}