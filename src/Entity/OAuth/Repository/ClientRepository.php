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
     * @param string $grantType
     * @param null|string|null $clientSecret
     * @param bool $mustValidateSecret
     * @return ClientEntityInterface
     */
    public function getClientEntity($clientIdentifier, $grantType, $clientSecret = null, $mustValidateSecret = true)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where('c.identifier = :id');
        $qb->setParameter('id', $clientIdentifier);
        $query = $qb->getQuery();
        $result = $query->getResult();
        return empty($result) ? null : $result[0];
    }

    /**
     * @param Client $client
     * @return Client
     */
    public function save(Client $client)
    {
        if (!$client->getIdentifier()) {
            $this->_em->persist($client);
        }
        $this->_em->flush($client);
        return $client;
    }

    /**
     * @param Client $client
     */
    public function delete(Client $client)
    {
        $this->_em->remove($client);
        $this->_em->flush($client);
    }
}