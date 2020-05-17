<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tournament extends AppModel
{
    use Filterable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'ban_count',
        'map_count',
    ];

    public static $loadable = [
        'maps',
        'matches',
        'matches.playerOne',
        'matches.playerTwo',
    ];

    public static $defaultLoaded = [
        'maps',
    ];

    public function maps()
    {
        return $this->belongsToMany(Map::class);
    }

    public function matches()
    {
        return $this->hasMany(Match::class);
    }
}
