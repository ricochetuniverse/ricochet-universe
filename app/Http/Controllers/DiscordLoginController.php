<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class DiscordLoginController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('discord')
            ->setScopes(['identify'])
            ->redirect();
    }

    public function handleProviderCallback()
    {
        $discordUser = Socialite::driver('discord')->user();

        if (!in_array($discordUser->id, config('services.discord.user_id_whitelist'))) {
            return response()
                ->view('auth.not-on-whitelist', ['discordUserId' => $discordUser->id], 403);
        }

        $user = User::firstOrCreate(
            ['discord_id' => $discordUser->id],
            [
                'name'               => $discordUser->nickname,
                'email'              => '',
                'password'           => '',
                'discord_avatar_url' => $discordUser->avatar,
            ]
        );

        Auth::guard()->login($user);

        return redirect()->intended('/');
    }
}
