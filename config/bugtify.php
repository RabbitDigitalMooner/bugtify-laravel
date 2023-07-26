<?php

return [
    'default' => 'discord',
    'enabled_limit_notification' => env('BUGTIFY_LIMIT_ENABLED', false),
    'max_limit_notification' => env('BUGTIFY_MAX_NOTIFY', 10),

    /*
    |--------------------------------------------------------------------------
    | Message length limitation
    |--------------------------------------------------------------------------
    |
    | We add support for a free discord users when type up to 2000 characters
    | per message. Discord will reject the request and notify will not display.
    | you can specify the length for title and description to control it not over
    | limitation.
    |
    */

    'limit_title' => env('BUGTIFY_TITLE_LIMIT', 1000),
    'limit_description' => env('BUGTIFY_DESCRIPTION_LIMIT', 1000),

    'discord' => [
        'webhook' => env('BUGTIFY_DISCORD_WEBHOOK'),
        'embed_color' => '15548997',
    ],
    'environments' => [
        'production',
        'staging',
    ],
];
