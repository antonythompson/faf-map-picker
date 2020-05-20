<?php

namespace App\Broadcasting;

use App\Models\Match;
use App\Models\User;

class MatchChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Match  $match
     * @return array|bool
     */
    public function join(User $user, Match $match)
    {
        return in_array($user->id, [$match->player_one_id, $match->player_two_id]);
    }

    public function via($notifiable)
    {
        return ['broadcast'];
    }
}
