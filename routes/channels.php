<?php

use App\Models\Match;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::channel('match.{match}', function ($user, Match $match) {
    return ['id' => $user->id, 'name' => $user->name, 'match' => $match];
    if (in_array($user->id, [$match->player_one_id, $match->player_two_id])) {
        return ['id' => $user->id, 'name' => $user->name];
    }
    return null;
});