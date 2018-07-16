<?php

namespace OAuthTest;

use Codeception\TestCase\Test;
use OAuth\OAuthUser;

class UserTest extends Test
{
    public function testGetSetIdentifier()
    {
        $scope = new OAuthUser();
        $scope->setID(345);
        $this->assertEquals(345, $scope->getIdentifier());
    }
}