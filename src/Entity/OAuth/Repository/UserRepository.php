<?php

namespace OAuth\Repository;

use Del\Common\ContainerService;
use Del\Criteria\UserCriteria;
use Del\Repository\UserRepository as UserRepo;
use Del\Service\UserService;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository extends UserRepo implements UserRepositoryInterface
{
    /**
     * @param string $username
     * @param string $password
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @return mixed
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        $container = ContainerService::getInstance()->getContainer();
        /** @var UserService $userService */
        $userService = $container['service.user'];
        if ($id = $userService->authenticate($username, $password)) {
            $user = $userService->findUserById($id);
            return $user;
        }
        return false;
    }
}