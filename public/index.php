<?php

//                            7 ,::::::::                         
//                          + :::::::::,  :                 ?     
//                          :::::::::: ::::::~              7777  
//      ,: 7               ::::::::: :: =7777~             7+777  
//        +  77777           :::::::::: 777777777           ,77+77=7  
//        ~77 :77777          ::::::=7,7777777777          77+777777  
//        77777777:77777      :::,777 ?7777777777~     +7777777777I?  
//    :777 7II7777777777  ::77     7~+     77:  ?7777777          
//      =?       : I7777 = :       77:     ~7 777777 ,            
//                     7 7 7      7  7     77,777 =               
//                     ,7 777    7I  777I77?7: ,                  
//                     , , 7777777    77777:                      
//                       7:   7777  ??777? ~                      
//                       :  ,7 7777777777 7I7 =                   
//                     , ::::77=77777?77777+7777I ?               
//                   777:: ::777:      +777 + ~77777              
//               777777  :,:::777:     777        777777      I7  
//     , 777777+I777    :::,::77777,~+7777I        ,,777I 777777, 
//      7 7777I77,~     :::::,: 777777777~           ~=7777 77:7  
//         77777 77 +      ,:::: ::7 777777I=               7777  77  
//     ,77?777           : : :      = ,                 +777?77~  
//     +777 7            :,:::~                            77I    
//        77?              +::                                    
//                         =::                                    
//                          :           ____   __   __ _  ____  _  _  _  _   ___
//                          :          (  _ \ /  \ (  ( \(  __)( \/ )/ )( \ / __)
//                                      ) _ ((  O )/    / ) _) / \/ \\ \/ /( (__
//                                     (____/ \__/ \_)__)(____)\_)(_/ \__/  \___)


/**
 *
 * I be settin' up th'application path
 *
 */
chdir(dirname(__DIR__));
if (!defined('APPLICATION_PATH'))
{
    define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
}

if (!defined('APPLICATION_ENV'))
{
    define('APPLICATION_ENV', (getenv('APPLICATION_ENV')
        ? getenv('APPLICATION_ENV')
        : 'production'));
}


/**
 *
 * I be autoloadin' th'composer or else shiver me timbers
 *
 */
if (!file_exists('vendor/autoload.php'))
{
    throw new RuntimeException(
        'Garrrr! Unable t\'load Bone. Run `composer install` or `php composer.phar install`'
    );
}
$loader = require_once 'vendor/autoload.php';

$server = new \Bone\Server\Environment($_SERVER);
$config = $server->fetchConfig(APPLICATION_PATH .'/config', APPLICATION_ENV);

// Set up our Dependency Injection Container
use Del\Common\Config\DbCredentials;
use Del\Common\ContainerService;
use Del\UserPackage;
use OAuth\OAuthPackage;

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

// Override the Del\Entity\User with our OAuth user class
/** @var \Del\Service\UserService $userService */
$userService = $container['service.user'];
$userService->setUserClass(\OAuth\OAuthUser::class);
$container['service.user'] = $userService;

ini_set('display_errors', false);
/**
 *
 *  Time t'begin th'voyage me hearties!
 *
 */
Bone\Mvc\Application::ahoy($config)->setSail();
