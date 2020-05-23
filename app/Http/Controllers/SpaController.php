<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpaController extends AppController
{

    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->load(['player', 'discord']);
        }
        return view('spa', [
            'loggedInUser' => $user,
        ]);
    }
}
