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
    if ($user->player && in_array($user->player->id, [$match->player_one_id, $match->player_two_id])) {
        return [
            'player_id' => $user->player->id,
            'login' => $user->player->login
        ];
    }
});