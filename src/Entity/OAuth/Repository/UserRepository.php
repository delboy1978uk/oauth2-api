<?php

namespace OAuth\Repository;

use Del\Repository\UserRepository as UserRepo;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository extends UserRepo implements UserRepositoryInterface
{
    public function getUserEntityByUserCredentials(
        $email,
        $password,
        $grantType,
        ClientEntityInterface $client
    )
    {
        // TODO: Implement getUserEntityByUserCredentials() method.
    }


    /**
     * @param $email
     * @param $password
     * @return mixed
     */
    public function checkUserCredentials($email, $password)
    {
        $user = $this->findOneBy(['email' => $email]);
        if ($user) {
            return $user->verifyPassword($password);
        }
        return false;
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getUserDetails($email)
    {
        $user = $this->findOneBy(['email' => $email]);
        if ($user) {
            $user = $user->toArray();
        }
        return $user;
    }

}