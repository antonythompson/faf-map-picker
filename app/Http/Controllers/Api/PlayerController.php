<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ResourceController;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class PlayerController extends ResourceController
{

    protected $resourceModel = Player::class;

    protected $paginate = false;

    protected $where = [];

    public function search(Request $request)
    {
        $users = [];
        $term = $request->input('term', false);
        if ($term) {
            $term = urlencode($term);
            $url = "https://api.faforever.com/data/player?filter=login=='*$term*'&page[size]=20";
            $json = file_get_contents($url);
            $data = json_decode($json, true);
            if (!empty($data['data'])) {
                foreach ($data['data'] as $i => $datum) {
                    $user = $datum['attributes'];
                    $user['faf_id'] = $datum['id'];
                    $users[] = $user;
                }
            }
        }
        return response()->json([
            'data' => $users
        ]);
    }

    public function createFaf(Request $request)
    {
        $faf_id = $request->input('faf_id');
        $player = Player::where('faf_id', $faf_id)->first();
        if (!$player) {
            $data = $request->all();
            $player = Player::create([
                'faf_id' => $faf_id,
                'login' => $data['login'],
            ]);
        }
        return response()
            ->json([
                'data' => $player
            ]);
    }
}
