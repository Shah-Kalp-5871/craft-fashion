<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPopupStat
 */
class PopupStat extends Model
{
    protected $fillable = [
        'popup_id',
        'session_id',
        'customer_id',
        'action',
        'ip_address',
        'user_agent',
        'page_data',
    ];

    protected $casts = [
        'page_data' => 'array',
    ];

    // Relationships
    public function popup(): BelongsTo
    {
        return $this->belongsTo(Popup::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}