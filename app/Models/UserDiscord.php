<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDiscord extends Model
{

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'snowflake_id',
        'user_id',
        'expires_at',
        'access_token',
        'refresh_token',
        'username',
        'discriminator',
        'avatar',
    ];

    protected $hidden = [
        'user_id',
        'username',
        'discriminator',
        'avatar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
