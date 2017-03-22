<?php

namespace OAuthTest;

use Codeception\TestCase\Test;
use DateTime;
use OAuth\AccessToken;
use OAuth\RefreshToken;

class RefreshTokenTest extends Test
{
    public function testGetSetIdentifier()
    {
        $token = new RefreshToken();
        $token->setIdentifier('asdfghjkl');
        $this->assertEquals('asdfghjkl', $token->getIdentifier());
    }

    public function testGetSetExpiryDateTime()
    {
        $date = new DateTime('2014-09-18');
        $token = new RefreshToken();
        $token->setExpiryDateTime($date);
        $this->assertInstanceOf('DateTime', $token->getExpiryDateTime());
        $this->assertEquals('2014-09-18', $token->getExpiryDateTime()->format('Y-m-d'));
    }

    public function testGetSetAccessToken()
    {
        $accessToken = new AccessToken();
        $accessToken->setIdentifier('abc');
        $token = new RefreshToken();
        $token->setAccessToken($accessToken);
        $this->assertInstanceOf('OAuth\AccessToken', $token->getAccessToken());
        $this->assertEquals('abc', $token->getAccessToken()->getIdentifier());
    }
}