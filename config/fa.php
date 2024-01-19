<?php

return [
    'api_token' => env('FA_API_TOKEN', null),
    'site_id' => env('FA_SITE_ID', null),
    'hostnames' => [
        env('FA_HOSTNAME'),
    ],
    'share_url' => env('FA_SHARE_URL', null),
];
