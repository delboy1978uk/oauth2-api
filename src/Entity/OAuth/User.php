<?php

namespace OAuth;

use Del\Entity\BaseUser;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * @MappedSuperclass
 * @Table(name="User")
 */
class User extends BaseUser implements UserEntityInterface
{
    /**
     * @return int
     */
    public function getIdentifier()
    {
        return $this->getId();
    }
}