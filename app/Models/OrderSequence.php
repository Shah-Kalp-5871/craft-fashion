<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperOrderSequence
 */
class OrderSequence extends Model
{
    protected $fillable = [
        'prefix',
        'last_number',
        'year',
        'month',
    ];
}