<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 *
 * @property int id
 * @property string email
 * @property string name
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use Filterable;
    use SoftDeletes;
    use Notifiable;
    use HasApiTokens;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'name',
    ];

    public function player()
    {
        return $this->hasOne(Player::class);
    }

    public function discord()
    {
        return $this->hasOne(UserDiscord::class);
    }
}
