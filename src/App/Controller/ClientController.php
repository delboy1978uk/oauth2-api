<?php

namespace App\Controller;

use Del\Common\ContainerService;
use OAuth\Client;
use OAuth\Repository\ClientRepository;

class ClientController extends BaseController
{
    /**
     *  We want to extend resource server controller
     *  And lock down on admin scope
     *
     * @return array|void
     */
    public function indexAction()
    {
        $container = ContainerService::getInstance()->getContainer();

        /** @var ClientRepository $clientRepository */
        $clientRepository = $container['repository.Client'];

        /** @var Client $user */
        $clients = $clientRepository->findAll();

        if (count($clients) == 0) {
            $this->sendJsonResponse(['No clients found']);
        }

        $this->sendJsonResponse($clients);
    }
}