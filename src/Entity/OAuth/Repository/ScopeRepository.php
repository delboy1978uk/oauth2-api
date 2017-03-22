<?php

namespace OAuth\Repository;

use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopeRepository extends EntityRepository implements ScopeRepositoryInterface
{
    /**
     * @param string $identifier
     * @return mixed
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        // TODO: Implement getScopeEntityByIdentifier() method.
    }

    /**
     * @param array|ScopeEntityInterface[] $scopes
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @param null|string|null $userIdentifier
     * @return mixed
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        // TODO: Implement finalizeScopes() method.
    }
}