<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSetting
 */
class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'options',
        'label',
        'description',
        'is_encrypted',
        'is_public',
        'sort_order',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
        'is_public' => 'boolean',
        'options' => 'array',
    ];
}
