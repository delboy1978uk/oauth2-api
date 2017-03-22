<?php

namespace OAuth\Repository;

use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository extends EntityRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getNewRefreshToken()
    {
        // TODO: Implement getNewRefreshToken() method.
    }

    /**
     * @param RefreshTokenEntityInterface $refreshTokenEntity
     * @return mixed
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        // TODO: Implement persistNewRefreshToken() method.
    }

    /**
     * @param string $tokenId
     * @return mixed
     */
    public function revokeRefreshToken($tokenId)
    {
        // TODO: Implement revokeRefreshToken() method.
    }

    /**
     * @param string $tokenId
     * @return mixed
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        // TODO: Implement isRefreshTokenRevoked() method.
    }
}