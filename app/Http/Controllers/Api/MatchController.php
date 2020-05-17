<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ResourceController;
use App\Models\Match;
use Illuminate\Http\Request;

class MatchController extends ResourceController
{

    protected $resourceModel = Match::class;

    protected $paginate = false;

    protected $where = [];

    public function banMap(Request $request, Match $match)
    {
        $player_id = $request->input('player_id');
        $map_id = $request->input('map_id');
        $match->load('bannedMaps');
        if (!count($match->bannedMaps)) {
            $match->bannedMaps()->attach($map_id, ['banned_by' => $player_id]);
        }
        $match->refresh();
        $match->load(['tournament.maps','playerOne','playerTwo','bannedMaps','pickedMaps']);
        return response()->json([
            'data' => $match
        ]);
    }

}
