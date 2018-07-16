<?php

namespace OAuthTest;

use Codeception\TestCase\Test;
use DateTime;
use OAuth\AccessToken;
use OAuth\Client;
use OAuth\Scope;
use OAuth\OAuthUser;

class AccessTokenTest extends Test
{
    public function testGetSetIdentifier()
    {
        $token = new AccessToken();
        $token->setIdentifier('asdfghjkl');
        $this->assertEquals('asdfghjkl', $token->getIdentifier());
    }

    public function testGetSetExpiryDateTime()
    {
        $date = new DateTime('2014-09-18');
        $token = new AccessToken();
        $token->setExpiryDateTime($date);
        $this->assertInstanceOf('DateTime', $token->getExpiryDateTime());
        $this->assertEquals('2014-09-18', $token->getExpiryDateTime()->format('Y-m-d'));
    }

    public function testGetSetClient()
    {
        $client = new Client();
        $client->setName('Dave.');
        $token = new AccessToken();
        $token->setClient($client);
        $this->assertInstanceOf('OAuth\Client', $token->getClient());
        $this->assertEquals('Dave.', $token->getClient()->getName());
    }

    public function testGetSetUser()
    {
        $user = new OAuthUser();
        $user->setID(6);
        $token = new AccessToken();
        $token->setUserIdentifier($user);
        $this->assertInstanceOf(OAuthUser::class, $token->getUserIdentifier());
        $this->assertEquals(6, $token->getUserIdentifier()->getID());
    }

    public function testAddGetScopes()
    {
        $scope = new Scope();
        $scope->setIdentifier('read_email');
        $token = new AccessToken();
        $token->addScope($scope);
        $this->assertTrue(is_array($token->getScopes()));
        $this->assertEquals(1, count($token->getScopes()));
        $this->assertInstanceOf('OAuth\Scope', $token->getScopes()[0]);
        $this->assertEquals('read_email', $token->getScopes()[0]->getIdentifier());
        $scope = new Scope();
        $scope->setIdentifier('see_private_info');
        $token->addScope($scope);
        $this->assertEquals(2, count($token->getScopes()));
        $this->assertInstanceOf('OAuth\Scope', $token->getScopes()[1]);
        $this->assertEquals('see_private_info', $token->getScopes()[1]->getIdentifier());
    }
}