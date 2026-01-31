<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperOrderStatusHistory
 */
class OrderStatusHistory extends Model
{
    protected $table = "order_status_history";
    protected $fillable = [
        'order_id',
        'status',
        'notes',
        'admin_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
