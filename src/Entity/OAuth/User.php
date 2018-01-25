<?php

namespace OAuth;

use Del\Entity\User as UserEntity;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * @Entity(repositoryClass="OAuth\Repository\UserRepository")
 * @Table(name="User")
 */
class User extends UserEntity implements UserEntityInterface
{
    /**
     * @return User
     */
    public function getIdentifier()
    {
        return $this->getID();
    }
}