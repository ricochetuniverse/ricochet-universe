<?php

return [
    'enable_sync_ratings' => env('RICOCHET_ENABLE_SYNC_RATINGS', false),

    'discord_upload_webhook' => env('RICOCHET_DISCORD_UPLOAD_WEBHOOK', ''),

    'discord_interaction_export_github_repo' => env('RICOCHET_DISCORD_INTERACTION_EXPORT_GITHUB_REPO', ''),

    'data_browser_categories' => [
        'ricochet-infinity-windows-build-68' => 'Ricochet Infinity (Windows, Build 68)',
    ],

    'discord_invite' => env('RICOCHET_DISCORD_INVITE', ''),
    'google_analytics_id' => env('RICOCHET_GOOGLE_ANALYTICS_ID', ''),
];
