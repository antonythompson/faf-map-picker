<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ResourceController;
use App\Models\Tournament;

class TournamentController extends ResourceController
{

    protected $resourceModel = Tournament::class;

    protected $paginate = false;

    protected $where = [];


}
