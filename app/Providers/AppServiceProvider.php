<?php

namespace App\Providers;

use App\Models\Player;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        View::composer(
//            ['spa'],
//            function ($view) {
//
//                //add user to view
//                if (Auth::check()) {
//                    $user = Auth::user()->load('player');
//                } else {
//                    $user = null;
//                }
////                $player = request()->cookie('player');
////                $player = Cookie::get('player');
//                $user = ['player' => $player];
//                $view->with('loggedInUser', json_encode($user));
//            }
//        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
