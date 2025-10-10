<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'discord' => [
        'client_id' => env('DISCORD_KEY'),
        'client_secret' => env('DISCORD_SECRET'), // OAuth2 client secret
        'public_key' => env('DISCORD_PUBLIC_KEY'),
        'redirect' => env('DISCORD_REDIRECT_URI'),
        'user_id_whitelist' => explode(',', env('DISCORD_USER_ID_WHITELIST', '')),

        'avatar_default_extension' => 'png',
    ],

    'github' => [
        'integration_id' => env('GITHUB_INTEGRATION_ID'),
        'installation_id' => (int) env('GITHUB_INSTALLATION_ID'),
        'signing_key_file' => storage_path(env('GITHUB_SIGNING_KEY_FILE')),
    ],

];
