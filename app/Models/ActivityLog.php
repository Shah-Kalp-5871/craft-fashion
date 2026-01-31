<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperActivityLog
 */
class ActivityLog extends Model
{
    protected $fillable = [
        'admin_id',
        'customer_id',
        'action',
        'entity_type',
        'entity_id',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
        'additional_data',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'additional_data' => 'array',
    ];

    // Relationships
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}