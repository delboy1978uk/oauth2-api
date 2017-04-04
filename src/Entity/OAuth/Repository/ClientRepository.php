<?php

namespace OAuth\Repository;

use Doctrine\ORM\EntityRepository;
use OAuth2\Storage\ClientCredentialsInterface;

class ClientRepository extends EntityRepository implements ClientCredentialsInterface
{
    /**
     * @param string $clientId
     * @param string|null $clientSecret
     * @return bool
     */
    public function checkClientCredentials($clientId, $clientSecret = null)
    {
        $client = $this->findOneBy(['clientIdentifier' => $clientId]);
        if ($client) {
            return $client->verifyClientSecret($clientSecret);
        }
        return false;
    }

    /**
     * @param $clientId
     * @return bool
     */
    public function isPublicClient($clientId)
    {
        return false;
    }

    /**
     * @param $clientId
     * @return null|array
     */
    public function getClientDetails($clientId)
    {
        $client = $this->findOneBy(['clientIdentifier' => $clientId]);
        if ($client) {
            $client = $client->toArray();
        }
        return $client;
    }

    /**
     * @param $client_id
     * @return null
     */
    public function getClientScope($client_id)
    {
        return null;
    }

    /**
     * @param $clientId
     * @param $grantType
     * @return bool
     */
    public function checkRestrictedGrantType($clientId, $grantType)
    {
        // we do not support different grant types per client in this example
        return true;
    }


}