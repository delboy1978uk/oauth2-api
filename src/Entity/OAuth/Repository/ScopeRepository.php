<?php

namespace OAuth\Repository;

use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use OAuth\Scope;

class ScopeRepository extends EntityRepository implements ScopeRepositoryInterface
{
    /**
     * @param string $identifier
     * @return null|Scope
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->where('s.identifier = :id');
        $qb->setParameter('id', $identifier);
        $query = $qb->getQuery();
        $result = $query->getResult();
        return empty($result) ? null : $result[0];
    }

    /**
     * @param array|ScopeEntityInterface[] $scopes
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @param null|string|null $userIdentifier
     * @return Scope[]
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        // we can code in further ACL functionality in here specific to our app
        return $scopes;
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