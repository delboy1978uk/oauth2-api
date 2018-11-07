<?php

namespace OAuthTest;

use Bone\Server\Environment;
use Codeception\TestCase\Test;
use Del\Common\Config\DbCredentials;
use Del\Common\ContainerService;
use OAuth\OAuthPackage;

class OAuthPackageTest extends Test
{
    public function testGetSetIdentifierRegisterPackages()
    {
        $env = new Environment([]);
        $config = $env->fetchConfig(APPLICATION_PATH . '/config', getenv('APPLICATION_ENV'));

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

        $package = new OAuthPackage();
        $svc->registerToContainer($package);

        $container = $svc->getContainer();

        $repository = $container['repository.AccessToken'];
        $this->assertInstanceOf('OAuth\Repository\AccessTokenRepository', $repository);
        $repository = $container['repository.AuthCode'];
        $this->assertInstanceOf('OAuth\Repository\AuthCodeRepository', $repository);
        $repository = $container['repository.Client'];
        $this->assertInstanceOf('OAuth\Repository\ClientRepository', $repository);
        $repository = $container['repository.RefreshToken'];
        $this->assertInstanceOf('OAuth\Repository\RefreshTokenRepository', $repository);
        $repository = $container['repository.Scope'];
        $this->assertInstanceOf('OAuth\Repository\ScopeRepository', $repository);
        $repository = $container['repository.User'];
        $this->assertInstanceOf('OAuth\Repository\UserRepository', $repository);
    }

}