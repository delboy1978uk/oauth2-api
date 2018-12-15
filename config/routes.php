<?php

return [
    'routes' => [
        '/' => [
            'controller' => 'index',
            'action' => 'index',
            'params' => [],
        ],
        '/client/[:id]' => [
            'controller' => 'client',
            'action' => 'index',
            'params' => [
                'scopes' => ['admin']
            ],
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
            'controller' => 'o-auth-server',
            'action' => 'authorize',
            'params' => [],
        ],
        '/oauth2/access-token' => [
            'controller' => 'o-auth-server',
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
        '/:locale/user/lost-password/:email' => [
            'controller' => 'user',
            'action' => 'lost-password',
            'params' => [],
        ],
        '/:locale/user/reset-password/:email/:token' => [
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
        '/website' => [
            'controller' => 'official-web-app',
            'action' => 'index',
            'params' => [],
        ],
        '/website/register' => [
            'controller' => 'official-web-app',
            'action' => 'register',
            'params' => [],
        ],
        '/website/thanks-for-registering' => [
            'controller' => 'official-web-app',
            'action' => 'thanks-for-registering',
            'params' => [],
        ],
        '/:locale/activate-user-account/:email/:token' => [
            'controller' => 'official-web-app',
            'action' => 'activate-user-account',
            'params' => [],
        ],
        '/website/client-credentials-example' => [
            'controller' => 'official-web-app',
            'action' => 'client-credentials-example',
            'params' => [],
        ],
    ],
];