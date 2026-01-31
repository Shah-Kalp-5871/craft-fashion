<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperUrlRedirect
 */
class UrlRedirect extends Model
{
    protected $fillable = [
        'source_url',
        'target_url',
        'redirect_type',
        'is_active',
        'hit_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}