<?php

namespace OAuth\Repository;

use Doctrine\ORM\EntityRepository;
use Exception;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use OAuth\AccessToken;

class AccessTokenRepository extends EntityRepository implements AccessTokenRepositoryInterface
{
    /**
     * @param AccessTokenEntityInterface $accessTokenEntity
     * @return AccessTokenEntityInterface
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): AccessTokenEntityInterface
    {
        $this->_em->persist($accessTokenEntity);
        $this->_em->flush();
        return $accessTokenEntity;
    }

    /**
     * @param string $tokenId
     * @throws Exception
     */
    public function revokeAccessToken($tokenId)
    {
        /** @var AccessToken $token */
        $token = $this->find($tokenId);
        if(!$token) {
            throw new Exception('Token not found', 404);
        }
        $token->setRevoked(true);
        $this->_em->flush($token);
    }

    /**
     * {@inheritdoc}
     */
    public function isAccessTokenRevoked($tokenId)
    {
        /** @var null|AccessToken $token */
        $token = $this->find($tokenId);
        if(!$token || $token->isRevoked()) {
            return true;
        }
        return false;
    }

    /**
     * @param ClientEntityInterface $clientEntity
     * @param array $scopes
     * @param null $userIdentifier
     * @return AccessTokenEntityInterface|AccessToken
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null): AccessToken
    {
        $token =  new AccessToken();

        return $token;
    }
}