<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSeoMetadata
 */
class SeoMetadata extends Model
{
    protected $fillable = [
        'entity_type',
        'entity_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots',
        'og_tags',
        'twitter_tags',
        'structured_data',
    ];

    protected $casts = [
        'og_tags' => 'array',
        'twitter_tags' => 'array',
        'structured_data' => 'array',
    ];

    public function entity()
    {
        return $this->morphTo();
    }
}