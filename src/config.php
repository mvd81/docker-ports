<?php

$config = [
    'rootDir' => '/var/www/html/projects',

    /*
     * Files where to get/scan docker-compose.json files.
     */
    'projectLocations' => [
        'Projects/**/**',
        'Sandbox/**/**',
    ],

    /*
     * Services
     */
    'services' => [
        'webserver' => ['nginx', 'apache'],
        'php' => ['php'],
        'db' => ['mysql', 'db'],
        'phpmyadmin' => ['phpmyadmin'],
        'redis' => ['redis']
    ],
];

