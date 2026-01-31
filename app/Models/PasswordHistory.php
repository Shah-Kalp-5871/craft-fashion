<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPasswordHistory
 */
class PasswordHistory extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'password_hash',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->morphTo();
    }
}