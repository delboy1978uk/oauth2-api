<?php

namespace OAuth\Repository;

use OAuth\Client;
use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository extends EntityRepository implements ClientRepositoryInterface
{
    /**
     * @param string $clientIdentifier
     * @param null|string $grantType
     * @param null|string|null $clientSecret
     * @param bool $mustValidateSecret
     *
     * @return ClientEntityInterface|null
     */
    public function getClientEntity($clientIdentifier, $grantType = null, $clientSecret = null, $mustValidateSecret = true)
    {
        /** @var Client $client */
        $client = $this->findOneBy([
            'identifier' => $clientIdentifier
        ]);

        if ($client instanceof Client == false) {
            return null;
        }

        if (
            $mustValidateSecret === true
            && $client->isConfidential() === true
            && $clientSecret != $client->getSecret()
        ) {
            return null;
        }

        return $client;
    }

    /**
     * @param Client $client
     * @return Client
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Client $client)
    {
        $this->_em->persist($client);
        $this->_em->flush($client);
        return $client;
    }

    /**
     * @param Client $client
     * @return Client
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Client $client)
    {
        $this->_em->flush($client);
        return $client;
    }

    /**
     * @param Client $client
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Client $client)
    {
        $this->_em->remove($client);
        $this->_em->flush($client);
    }
}