<?php



// Add the widget to the widgets array in config/statamic/cp.php
// [
//     'type' => 'fathom_analytics',
//     'width' => 100,
// ],

return [
    'fathom_api_token' => env('FATHOM_API_TOKEN', null),
    'fathom_site_id' => env('FATHOM_SITE_ID', null),
    'fathom_hostname' => env('FATHOM_HOSTNAME', null),
];
