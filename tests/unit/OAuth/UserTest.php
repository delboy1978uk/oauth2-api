<?php

namespace OAuthTest;

use Codeception\TestCase\Test;
use OAuth\User;

class UserTest extends Test
{
    public function testGetSetIdentifier()
    {
        $scope = new User();
        $scope->setID(345);
        $this->assertEquals(345, $scope->getIdentifier());
    }
}