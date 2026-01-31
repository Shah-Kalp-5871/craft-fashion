<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeSection extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'type',
        'category_id',
        'product_ids',
        'style',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'product_ids' => 'array',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
