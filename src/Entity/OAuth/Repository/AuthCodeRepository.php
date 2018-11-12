<?php

namespace OAuth\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Exception;
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
     * @throws Exception
     */
    public function revokeAuthCode($codeId)
    {
        /** @var AuthCode $token */
        $code = $this->find($codeId);
        if(!$code) {
            throw new Exception('Token not found', 404);
        }
        $code->setRevoked(true);
        $this->_em->flush($code);
    }

    /**
     * @param string $codeId
     * @return mixed
     */
    public function isAuthCodeRevoked($codeId)
    {
        /** @var AuthCode $code */
        $code = $this->findOneBy(['identifier' => $codeId]);
        if (!$code || $code->getExpiryDateTime() < new DateTime() || $code->isRevoked()) {
            return true;
        }
        return false;
    }
}