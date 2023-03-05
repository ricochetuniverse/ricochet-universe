<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class DiscordLoginController extends Controller
{
    public function redirectToProvider()
    {
        // @phpstan-ignore-next-line
        return Socialite::driver('discord')
            ->setScopes(['identify'])
            ->redirect();
    }

    public function handleProviderCallback()
    {
        $discordUser = Socialite::driver('discord')->user();

        if (! in_array($discordUser->getId(), config('services.discord.user_id_whitelist'))) {
            return response()
                ->view('auth.not-on-whitelist', ['discordUserId' => $discordUser->getId()], 403);
        }

        $user = User::updateOrCreate(
            ['discord_id' => $discordUser->getId()],
            [
                'name' => $discordUser->getNickname(),
                'email' => '',
                'password' => '',
                'discord_avatar_url' => $discordUser->getAvatar(),
            ]
        );

        Auth::guard()->login($user);

        return redirect()->intended('/');
    }
}
