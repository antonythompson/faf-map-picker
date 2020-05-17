<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AppController;
use App\Models\User;
use App\Models\UserDiscord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Socialite;

class LoginController extends AppController
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('discord')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        /**
        SocialiteProviders\Manager\OAuth2\User {#1237 ▼
        +accessTokenResponseBody: array:5 [▼
            "access_token" => "NEw8MY7wZntzB2xk9TEVrhU4EEZbzx"
            "expires_in" => 604800
            "refresh_token" => "QCwMbbwZ8lPC4WYOBOgjBz1ot8S5jR"
            "scope" => "identify email"
            "token_type" => "Bearer"
        ]
        +token: "NEw8MY7wZntzB2xk9TEVrhU4EEZbzx"
        +refreshToken: "QCwMbbwZ8lPC4WYOBOgjBz1ot8S5jR"
        +expiresIn: 604800
        +id: "401536987326185472"
        +nickname: "Antz#6868"
        +name: "Antz"
        +email: "bloodantz14@gmail.com"
        +avatar: "https://cdn.discordapp.com/avatars/401536987326185472/ffcee583761f137baa27d4a68c2560a1.jpg"
        +user: array:10 [▼
            "id" => "401536987326185472"
            "username" => "Antz"
            "avatar" => "ffcee583761f137baa27d4a68c2560a1"
            "discriminator" => "6868"
            "public_flags" => 0
            "flags" => 0
            "email" => "bloodantz14@gmail.com"
            "verified" => true
            "locale" => "en-US"
            "mfa_enabled" => false
        ]
        }
         */
        $auth_user = Socialite::driver('discord')->user();
//        echo '<pre>';print_r($auth_user);exit;
//        dd(json_decode(json_encode($auth_user, true)));
        $user = User::where('email', $auth_user->email)
            ->with('discord')
            ->first();

        if (!$user) {
            $user = User::create([
                'name' => $auth_user->name,
                'email' => $auth_user->email
            ]);
        }

        $discord_args = [
            'snowflake_id' => $auth_user->id,
            'user_id' => $user->id,
            'access_token' => $auth_user->token,
            'refresh_token' => $auth_user->refreshToken,
            'expires_at' => Carbon::now()->addSecond($auth_user->expiresIn),
            'username' => $auth_user->user['username'],
            'discriminator' => $auth_user->user['discriminator'],
            'avatar' => $auth_user->avatar,
        ];

        if ($user->discord) {
            $user->discord->fill($discord_args)->save();
        } else {
            $discord = UserDiscord::create($discord_args);
            $user->setRelation('discord', $discord);
        }

        Auth::login($user);

        return redirect('/');
    }
}