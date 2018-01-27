<?php

namespace OAuth;

use Del\Entity\User as UserEntity;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * @MappedSuperclass
 * @Entity(repositoryClass="OAuth\Repository\UserRepository") // problems? comment out then migrate and uncomment ;-)
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