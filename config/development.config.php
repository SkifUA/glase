<?php

return [
    'modules' => [
//        'ZendDeveloperTools',
    ],
    'module_listener_options' => [
        // Turn off caching
        'config_glob_paths' => [realpath(__DIR__) . '/autoload/{,*.}{global,local}-development.php'],
        'config_cache_enabled'     => false,
        'module_map_cache_enabled' => false,
    ],
    'view_manager' => [
        'display_exceptions' => true,
    ],
];
