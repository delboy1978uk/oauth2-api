<?php

namespace OAuth\Repository;

use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use OAuth\RefreshToken;

/**
 * Class RefreshTokenRepository
 * @package OAuth\Repository
 */
class RefreshTokenRepository extends EntityRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @return RefreshToken
     */
    public function getNewRefreshToken()
    {
        return new RefreshToken();
    }

    /**
     * @param RefreshTokenEntityInterface $refreshTokenEntity
     * @return RefreshTokenEntityInterface
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $this->_em->persist($refreshTokenEntity);
        $this->_em->flush();

        return $refreshTokenEntity;
    }

    /**
     * @param string $tokenId
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function revokeRefreshToken($tokenId)
    {
        $token = $this->_em->find(RefreshToken::class, $tokenId);
        if ($token instanceof RefreshTokenEntityInterface) {
            $this->_em->remove($token);
            $this->_em->flush();

            return true;
        }

        return false;
    }

    /**
     * @param string $tokenId
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        $token = $this->_em->find(RefreshToken::class, $tokenId);
        if ($token instanceof RefreshTokenEntityInterface) {

            return false;
        }

        return true;
    }
}