<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCustomerSegmentMember
 */
class CustomerSegmentMember extends Model
{
    protected $fillable = [
        'customer_id',
        'customer_segment_id',
        'added_at',
    ];

    protected $casts = [
        'added_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function segment()
    {
        return $this->belongsTo(CustomerSegment::class, 'customer_segment_id');
    }
}