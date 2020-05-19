<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Match
 *
 * @property int id
 * @property int tournament_id
 * @property int player_one_id
 * @property int player_two_id
 * @property int status_id
 * @property-read Player playerOne
 * @property-read Player playerTwo
 *
 * @package App\Models
 */
class Match extends AppModel
{
    use Filterable;
    use SoftDeletes;

    const STATUS_COMPLETE = 1;
    const STATUS_PENDING = 2;


    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tournament_id',
        'player_one_id',
        'player_two_id',
        'status_id',
    ];

    public static $defaultLoaded = [
        'tournament',
    ];

    public static $loadable = [
        'tournament',
        'tournament.maps',
        'playerOne',
        'playerTwo',
        'bannedMaps',
        'pickedMaps'
    ];

    public static $storeValidationFields = [
        'tournament_id' => 'required|exists:tournaments,id',
        'player_one_id' => 'required|exists:players',
        'player_two_id' => 'required|exists:players',
    ];

    public static $updateValidationFields = [
        'id' => 'required|exists:matches',
        'tournament_id' => 'required|exists:tournaments,id',
        'player_one_id' => 'required|exists:players',
        'player_two_id' => 'required|exists:players',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function playerOne()
    {
        return $this->belongsTo(Player::class);
    }

    public function playerTwo()
    {
        return $this->belongsTo(Player::class);
    }

    public function bannedMaps()
    {
        return $this->belongsToMany(Map::class, 'match_banned_maps')
            ->withPivot(['banned_by'])
            ->withTimestamps();
    }

    public function pickedMaps()
    {
        return $this->belongsToMany(Map::class, 'match_picked_maps')
            ->withPivot(['picked_by'])
            ->withTimestamps();
    }
}
