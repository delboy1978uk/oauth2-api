<?php

namespace OAuth\Repository;

use Doctrine\ORM\EntityRepository;
use Exception;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use OAuth\Client;
use OAuth\Scope;

class ScopeRepository extends EntityRepository implements ScopeRepositoryInterface
{
    /**
     * @param string $identifier
     * @return null|Scope
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        /** @var Scope $scope */
        $scope = $this->findOneBy(['identifier' => $identifier]);
        return $scope;
    }

    /**
     * @param array $scopes
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @param null|string $userIdentifier
     * @return ScopeEntityInterface[]
     * @throws Exception
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        /** @var Client $clientEntity */
        $clientScopes = $clientEntity->getScopes()->getValues();
        $finalScopes = array_uintersect($scopes, $clientScopes, function($a, $b) {
            return strcmp(spl_object_hash($a), spl_object_hash($b));
        });

        if (count($finalScopes) < count($scopes)) {
            throw new Exception('Scopes not authorised.', 403);
        }

        return $finalScopes;
    }

    /**
     * @param Scope $scope
     * @return Scope
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Scope $scope)
    {
        $this->_em->persist($scope);
        $this->_em->flush($scope);
        return $scope;
    }

    /**
     * @param Scope $scope
     * @return Scope
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Scope $scope)
    {
        $this->_em->flush($scope);
        return $scope;
    }
}