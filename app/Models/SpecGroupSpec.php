<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSpecGroupSpec
 */
class SpecGroupSpec extends Model
{
    protected $fillable = [
        'spec_group_id',
        'specification_id',
        'sort_order',
    ];

    public function group()
    {
        return $this->belongsTo(SpecificationGroup::class, 'spec_group_id');
    }

    public function specification()
    {
        return $this->belongsTo(Specification::class);
    }
}