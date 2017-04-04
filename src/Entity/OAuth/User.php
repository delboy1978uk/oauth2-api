<?php
/**
 * Created by PhpStorm.
 * User: DM0C60544
 * Date: 21/3/2017
 * Time: 4:17 PM
 */

namespace OAuth;

use Del\Entity\User as UserEntity;
use OAuth\Traits\EncryptableFieldEntity;

/**
 * @MappedSuperclass(repositoryClass="OAuth\Repository\UserRepository")
 */
class User extends UserEntity
{
    use EncryptableFieldEntity;

    /**
     * Verify user's password
     *
     * @param string $password
     * @return Boolean
     */
    public function verifyPassword($password)
    {
        return $this->verifyEncryptedFieldValue($this->getPassword(), $password);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'user_id' => $this->getID(),
            'scope' => null,
        ];
    }
}