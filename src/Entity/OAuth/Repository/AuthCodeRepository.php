<?php

namespace OAuth\Repository;

use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use OAuth\AuthCode;

class AuthCodeRepository extends EntityRepository implements AuthCodeRepositoryInterface
{
    /**
     * @return AuthCode
     */
    public function getNewAuthCode()
    {
        return new AuthCode();
    }

    /**
     * @param AuthCodeEntityInterface $authCodeEntity
     * @return mixed
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        // TODO: Implement persistNewAuthCode() method.
    }

    /**
     * @param string $codeId
     * @return mixed
     */
    public function revokeAuthCode($codeId)
    {
        // TODO: Implement revokeAuthCode() method.
    }

    /**
     * @param string $codeId
     * @return mixed
     */
    public function isAuthCodeRevoked($codeId)
    {
        // TODO: Implement isAuthCodeRevoked() method.
    }
}