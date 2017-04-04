<?php


$config = array(
    'routes' => array(
        '/' => array(
            'controller' => 'index',
            'action' => 'index',
            'params' => array(),
        ),
        '/ping' => array(
            'controller' => 'oauth',
            'action' => 'ping',
            'params' => array(),
        ),
        '/access-token' => array(
            'controller' => 'access-token',
            'action' => 'access-token',
            'params' => array(),
        ),
        '/resource' => array(
            'controller' => 'resource',
            'action' => 'resource',
            'params' => array(),
        ),
        '/auth' => array(
            'controller' => 'authorisation',
            'action' => 'auth',
            'params' => array(),
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

// Do not edit below this

// Set up our Dependency Injection Container
use Del\Common\Config\DbCredentials;
use Del\Common\ContainerService;
use Del\UserPackage;
use OAuth\OAuthPackage;
use OAuth\Repository\UserRepository;

$containerSvc = ContainerService::getInstance();

$dbname = $config['db']['database'];
$user = $config['db']['user'];
$pass = $config['db']['pass'];
$host = $config['db']['host'];

$credentials = new DbCredentials();
$credentials->setUser($user)
    ->setPassword($pass)
    ->setDatabase($dbname)
    ->setHost($host);

$svc = ContainerService::getInstance();
$svc->setDbCredentials($credentials);
$svc->setProxyPath(realpath(APPLICATION_PATH.'/data/proxies'));
$svc->registerToContainer(new UserPackage());
$svc->registerToContainer(new OAuthPackage());
$container = $svc->getContainer(); // Running this once after above setup creates the container for future use

// Override the Del\Entity\User with our OAuth super class
/** @var \Del\Service\UserService $userService */
$userService = $container['service.user'];
$userService->setUserClass('\OAuth\User');
$container['service.user'] = $userService;

return $config;

