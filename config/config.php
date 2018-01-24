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
            'controller' => 'oauth',
            'action' => 'ping',
            'params' => [],
        ),
        '/access-token' => array(
            'controller' => 'oauth',
            'action' => 'access-token',
            'params' => [],
        ),
        '/authorize' => array(
            'controller' => 'auth-code',
            'action' => 'authorize',
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

