<?php

namespace App\Http\Controllers;

use App\Services\DiscordApi\Enums\InstallationContext;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Discord\Provider;

class DiscordLoginController extends Controller
{
    public function redirectToProvider(Request $request)
    {
        /** @var Provider $socialite */
        $socialite = Socialite::driver('discord')
            // @phpstan-ignore-next-line
            ->setScopes(['identify']);

        if ($request->input('commands')) {
            $socialite->with(['integration_type' => InstallationContext::USER_INSTALL])
                ->scopes('applications.commands');
        }

        return $socialite->redirect();
    }

    public function handleProviderCallback()
    {
        $discordUser = Socialite::driver('discord')->user();

        if (! in_array($discordUser->getId(), config('services.discord.user_id_whitelist'), true)) {
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
