<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DiscordRedirectController extends Controller
{
    public function index()
    {
        $invite = config('ricochet.discord_invite');
        if (!$invite) {
            throw new NotFoundHttpException;
        }

        return redirect('https://discord.gg/' . $invite);
    }
}
