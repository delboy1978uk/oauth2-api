<?php


$config = array(
    'routes' => array(
        '/' => array(
            'controller' => 'index',
            'action' => 'index',
            'params' => array(),
        ),
        '/docs/api.json' => array(
            'controller' => 'index',
            'action' => 'api',
            'params' => array(),
        ),
        '/ping' => array(
            'controller' => 'index',
            'action' => 'ping',
            'params' => [],
        ),
        '/oauth2/authorize' => array(
            'controller' => 'auth-code',
            'action' => 'authorize',
            'params' => [],
        ),
        '/oauth2/access-token' => array(
            'controller' => 'access-token',
            'action' => 'access-token',
            'params' => [],
        ),
    ),
    'db' => array(
            'host' => '127.0.0.1',
            'database' => 'testapi',
            'user' => 'root',
            'pass' => ''
    ),
    'templates' => array(
        'layout'
    ),
);

return $config;

