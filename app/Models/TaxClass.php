<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperTaxClass
 */
class TaxClass extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Relationships
    public function rates(): HasMany
    {
        return $this->hasMany(TaxRate::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function defaultRate(): HasOne
    {
        return $this->hasOne(TaxRate::class)->where('is_active', true)->orderBy('priority');
    }

    public function getTotalRateAttribute()
    {
        return $this->rates()->where('is_active', true)->sum('rate');
    }
}
