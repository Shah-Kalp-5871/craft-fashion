<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperMedia
 */
class Media extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'file_name',
        'file_path',
        'disk',
        'mime_type',
        'file_type',
        'file_size',
        'thumbnails',
        'metadata',
        'alt_text',
        'uploaded_by',
        'uploader_type',
    ];

    protected $casts = [
        'thumbnails' => 'array',
        'metadata' => 'array',
        'file_size' => 'integer',
    ];

    // Relationships
    public function uploader(): MorphTo
    {
        return $this->morphTo('uploader');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'image_id');
    }

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class, 'logo_id');
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'image_id');
    }

    public function variantImages(): HasMany
    {
        return $this->hasMany(VariantImage::class);
    }

    public function reviewImages(): HasMany
    {
        return $this->hasMany(ReviewImage::class);
    }
}