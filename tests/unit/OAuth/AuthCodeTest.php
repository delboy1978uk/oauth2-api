<?php

namespace OAuthTest;

use Codeception\TestCase\Test;
use DateTime;
use OAuth\AuthCode;
use OAuth\Client;
use OAuth\Scope;
use OAuth\OAuthUser;

class AuthCodeTest extends Test
{
    public function testGetSetIdentifier()
    {
        $code = new AuthCode();
        $code->setIdentifier('asdfghjkl');
        $this->assertEquals('asdfghjkl', $code->getIdentifier());
    }

    public function testGetSetRedirectUri()
    {
        $code = new AuthCode();
        $code->setRedirectUri('http://nasa.gov');
        $this->assertEquals('http://nasa.gov', $code->getRedirectUri());
    }

    public function testGetSetExpiryDateTime()
    {
        $date = new DateTime('2014-09-18');
        $code = new AuthCode();
        $code->setExpiryDateTime($date);
        $this->assertInstanceOf('DateTime', $code->getExpiryDateTime());
        $this->assertEquals('2014-09-18', $code->getExpiryDateTime()->format('Y-m-d'));
    }

    public function testGetSetClient()
    {
        $client = new Client();
        $client->setName('Dave.');
        $code = new AuthCode();
        $code->setClient($client);
        $this->assertInstanceOf('OAuth\Client', $code->getClient());
        $this->assertEquals('Dave.', $code->getClient()->getName());
    }

    public function testGetSetUser()
    {
        $user = new OAuthUser();
        $user->setID(6);
        $code = new AuthCode();
        $code->setUserIdentifier($user);
        $this->assertInstanceOf(OAuthUser::class, $code->getUserIdentifier());
        $this->assertEquals(6, $code->getUserIdentifier()->getID());
    }

    public function testAddGetScopes()
    {
        $scope = new Scope();
        $scope->setIdentifier('read_email');
        $code = new AuthCode();
        $code->addScope($scope);
        $this->assertTrue(is_array($code->getScopes()));
        $this->assertEquals(1, count($code->getScopes()));
        $this->assertInstanceOf('OAuth\Scope', $code->getScopes()[0]);
        $this->assertEquals('read_email', $code->getScopes()[0]->getIdentifier());
        $scope = new Scope();
        $scope->setIdentifier('see_private_info');
        $code->addScope($scope);
        $this->assertEquals(2, count($code->getScopes()));
        $this->assertInstanceOf('OAuth\Scope', $code->getScopes()[1]);
        $this->assertEquals('see_private_info', $code->getScopes()[1]->getIdentifier());
    }
}