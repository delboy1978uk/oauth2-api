<?php

namespace OAuth\Repository;

use Del\Repository\UserRepository as UserRepo;
use OAuth2\Storage\UserCredentialsInterface;

class UserRepository extends UserRepo implements UserCredentialsInterface
{
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