<?php

namespace App\Http\Controllers\Api;

use App\Events\MatchImHere;
use App\Http\Controllers\ResourceController;
use App\Models\Match;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchController extends ResourceController
{

    protected $resourceModel = Match::class;

    protected $paginate = false;

    protected $where = [];

    public function banMap(Request $request, Match $match)
    {
        $player_id = $request->input('player_id'); //TODO replace with Auth::user()
        $map_id = $request->input('map_id');
        $match->load('bannedMaps');
        if (!$match->isMapBanned($map_id)) {
            $match->bannedMaps()->attach($map_id, ['banned_by' => $player_id]);
        }
        $match->refresh();
        $match->load(['bannedMaps']);
        if (count($match->bannedMaps) === ($match->tournament->ban_count * 2)) {
            //banning is done!!
            $banned_ids = $match->bannedMaps->pluck('id')->all();
            $map_ids = $match->tournament->maps->pluck('id')->all();
            $left = array_diff($map_ids, $banned_ids);
            $picks = collect($left)->random($match->tournament->map_count);
            foreach ($picks as $i => $id) {
                $match->pickedMaps()->attach($id, ['order' => $i + 1]);
            }
            $match->status_id = Match::STATUS_COMPLETE;
        }
        $match->load('pickedMaps');

        return response()->json([
            'data' => $match
        ]);
    }

    public function test(Request $request)
    {
        dd(Auth::user());
    }


}
