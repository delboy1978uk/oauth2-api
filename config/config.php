<?php


$config = [
    'routes' => [
        '/' => [
            'controller' => 'index',
            'action' => 'index',
            'params' => [],
        ],
        '/docs/api.json' => [
            'controller' => 'index',
            'action' => 'api',
            'params' => [],
        ],
        '/ping' => [
            'controller' => 'index',
            'action' => 'ping',
            'params' => [],
        ],
        '/oauth2/authorize' => [
            'controller' => 'auth-code',
            'action' => 'authorize',
            'params' => [],
        ],
        '/oauth2/access-token' => [
            'controller' => 'auth-code',
            'action' => 'access-token',
            'params' => [],
        ],
        '/fake-client-callback' => [
            'controller' => 'index',
            'action' => 'fake-client-callback',
            'params' => [],
        ],
    ],
    'db' => [
            'host' => '127.0.0.1',
            'database' => 'testapi',
            'user' => 'root',
            'pass' => ''
    ],
    'templates' => [
        'layout'
    ],
];

return $config;

