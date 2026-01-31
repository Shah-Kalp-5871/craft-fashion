<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperHomePageSection
 */
class HomePageSection extends Model
{
    protected $fillable = [
        'name',
        'title',
        'content',
        'type',
        'data',
        'display_rules',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
        'display_rules' => 'array',
        'status' => 'boolean',
    ];
}