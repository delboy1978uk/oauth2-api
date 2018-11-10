<?php

return [
    'routes' => [
        '/' => [
            'controller' => 'index',
            'action' => 'index',
            'params' => [],
        ],
        '/client' => [
            'controller' => 'client',
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
        '/:locale/user/register' => [
            'controller' => 'user',
            'action' => 'register',
            'params' => [],
        ],
        '/:locale/user/activate/resend/:email' => [
            'controller' => 'user',
            'action' => 'resend-activation',
            'params' => [],
        ],
        '/:locale/user/activate/:email/:token' => [
            'controller' => 'user',
            'action' => 'activate',
            'params' => [],
        ],
        '/user/lost-password/:email' => [
            'controller' => 'user',
            'action' => 'lost-password',
            'params' => [],
        ],
        '/user/reset-password/:email/:token' => [
            'controller' => 'user',
            'action' => 'reset-pass',
            'params' => [],
        ],
        '/user/:id' => [
            'controller' => 'user',
            'action' => 'index',
            'params' => [],
        ],
        '/fake-client-callback' => [
            'controller' => 'index',
            'action' => 'fake-client-callback',
            'params' => [],
        ],
        '/website/register' => [
            'controller' => 'first-party-website',
            'action' => 'register',
            'params' => [],
        ],
    ],
];

