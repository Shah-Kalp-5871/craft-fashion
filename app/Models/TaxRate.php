<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperTaxRate
 */
class TaxRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tax_class_id',
        'name',
        'country_code',
        'state_code',
        'zip_code',
        'rate',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    // Relationships
public function taxClass(): BelongsTo
{
    return $this->belongsTo(TaxClass::class)->withTrashed();
}

}
