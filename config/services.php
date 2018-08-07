<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'discord' => [
        'client_id'         => env('DISCORD_KEY'),
        'client_secret'     => env('DISCORD_SECRET'),
        'redirect'          => env('DISCORD_REDIRECT_URI'),
        'user_id_whitelist' => explode(',', env('DISCORD_USER_ID_WHITELIST')),
    ],

];
