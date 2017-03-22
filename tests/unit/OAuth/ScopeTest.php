<?php

namespace OAuthTest;

use Codeception\TestCase\Test;
use OAuth\Scope;

class ScopeTest extends Test
{
    public function testGetSetIdentifier()
    {
        $scope = new Scope();
        $scope->setIdentifier('asdfghjkl');
        $this->assertEquals('asdfghjkl', $scope->getIdentifier());
    }

    public function testJsonSerialise()
    {
        $scope = new Scope();
        $scope->setIdentifier('asdfghjkl');
        $this->assertEquals('asdfghjkl', $scope->jsonSerialize());
    }
}