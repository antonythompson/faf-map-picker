<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ResourceController;
use App\Models\Map;
use Illuminate\Http\Request;

class MapController extends ResourceController
{

    protected $resourceModel = Map::class;

    protected $paginate = false;

    protected $where = [];

    public function search(Request $request)
    {
        $maps = [];
        $term = $request->input('term', false);
        $show_hidden = $request->input('show_hidden', false);
        if ($term) {
            $term = urlencode($term);
            $url = "https://api.faforever.com/data/map?include=latestVersion&page[size]=20&filter=displayName==\"*$term*\"";
            $json = file_get_contents($url);
            $data = json_decode($json, true);

            if (!empty($data['data'])) {
                foreach ($data['data'] as $i => $datum) {
                    $map = $data['included'][$i]['attributes'];
                    $map['faf_id'] = $datum['id'];
                    $map['displayName'] = $datum['attributes']['displayName'];
                    if (!$map['hidden'] || $show_hidden) {
                        $maps[] = $map;
                    }
                }
            }
        }
        return response()->json([
            'data' => $maps
        ]);
    }
}
