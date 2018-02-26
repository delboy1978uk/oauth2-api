<?php

namespace OAuth\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use OAuth\AuthCode;
use OAuth\Client;

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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $date = new DateTime();
        $date->modify('+24 hours');
        $authCodeEntity->setExpiryDateTime($date);
        /** @var Client $client */
        $client = $this->_em->getRepository(Client::class)
                    ->findOneBy(['identifier' => $authCodeEntity->getClient()->getIdentifier()]);
        $authCodeEntity->setClient($client);
        $this->_em->persist($authCodeEntity);
        $this->_em->flush();
        return;
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
        /** @var AuthCode $code */
        $code = $this->findOneBy(['identifier' => $codeId]);
        if (!$code || $code->getExpiryDateTime() < new DateTime()) {
            return true;
        }
        return false;
    }
}