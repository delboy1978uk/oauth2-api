<?php

namespace OAuth\Service;

use OAuth\Repository\ClientRepository;

/**
 * Class ClientService
 * @package Entity\OAuth\Service
 */
class ClientService
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * ClientService constructor.
     * @param ClientRepository $clientRepository
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @return ClientRepository
     */
    public function getClientRepository(): ClientRepository
    {
        return $this->clientRepository;
    }
}