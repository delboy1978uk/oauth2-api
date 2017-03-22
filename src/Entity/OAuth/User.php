<?php
/**
 * Created by PhpStorm.
 * User: DM0C60544
 * Date: 21/3/2017
 * Time: 4:17 PM
 */

namespace OAuth;

use Del\Entity\User as UserEntity;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * @MappedSuperclass
 */
class User extends UserEntity implements UserEntityInterface
{
    /**
     * @return int
     */
    public function getIdentifier()
    {
        return $this->getID();
    }

}