<?php

return [
    'enable_sync_ratings' => env('APP_ENV') === 'local' || env('APP_ENV') === 'testing',

    'google_analytics_id' => env('RICOCHET_GOOGLE_ANALYTICS_ID', ''),
    'discord_invite' => env('RICOCHET_DISCORD_INVITE', ''),
    'discord_upload_webhook' => env('RICOCHET_DISCORD_UPLOAD_WEBHOOK', ''),
];
