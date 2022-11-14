<?php

return [
    'default' => 'discord',
    'enabled_limit_notification' => env('BUGTIFY_LIMIT_ENABLED', false),
    'max_limit_notification' => env('BUGTIFY_MAX_NOTIFY', 10),
    'lines_count' => 2000,
    'discord' => [
        'webhook' => env('BUGTIFY_DISCORD_WEBHOOK'),
        'embed_color' => '15548997',
    ],
    'environments' => [
        'production',
        'staging',
    ],
];
