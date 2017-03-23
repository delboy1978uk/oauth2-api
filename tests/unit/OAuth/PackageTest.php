<?php

namespace OAuthTest;

use Codeception\TestCase\Test;
use Del\Common\ContainerService;
use OAuth\OAuthPackage;

class OAuthPackageTest extends Test
{
    public function testGetSetIdentifierRegisterPackages()
    {
        require_once APPLICATION_PATH.'/config/config.php';
        $package = new OAuthPackage();
        $svc = ContainerService::getInstance();
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