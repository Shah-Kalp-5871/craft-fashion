<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperPopup
 */
class Popup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'title',
        'content',
        'type',
        'trigger',
        'delay_seconds',
        'display_rules',
        'targeting_rules',
        'starts_at',
        'ends_at',
        'status',
        'impressions',
        'conversions',
    ];

    protected $casts = [
        'display_rules' => 'array',
        'targeting_rules' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'status' => 'boolean',
    ];

    // Relationships
    public function stats(): HasMany
    {
        return $this->hasMany(PopupStat::class);
    }
}