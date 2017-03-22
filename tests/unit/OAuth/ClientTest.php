<?php

namespace OAuthTest;

use Codeception\TestCase\Test;
use OAuth\Client;

class ClientTest extends Test
{

    public function testGetSetIdentifier()
    {
        $client = new Client();
        $client->setIdentifier('asdfghjkl');
        $this->assertEquals('asdfghjkl', $client->getIdentifier());
    }

    public function testGetSetName()
    {
        $client = new Client();
        $client->setName('iPhone App');
        $this->assertEquals('iPhone App', $client->getName());
    }

    public function testGetSetRedirectUri()
    {
        $client = new Client();
        $client->setRedirectUri('https://nsa.gov');
        $this->assertEquals('https://nsa.gov', $client->getRedirectUri());
    }
}