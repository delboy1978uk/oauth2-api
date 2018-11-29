<?php

namespace App\Controller;

use Del\Common\ContainerService;
use OAuth\Client;
use OAuth\Exception\OAuthException;
use OAuth\Repository\ClientRepository;

class ClientController extends ResourceServerController
{
    /** @var ClientRepository $clientRepository */
    private $clientRepository;

    /**
     * Fetch client information - admin clients only
     *
     * @OA\Get(
     *     path="/client/{id}",
     *     tags={"client"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="the type of response",
     *         required=false,
     *         default=1
     *     ),
     *     @OA\Response(response="200", description="Sends client details"),
     *     security={
     *      {"clientCredentials": {"admin"}}
     *     }
     * )
     *
     * @throws OAuthException
     *
     * @return array|void
     */
    public function indexAction()
    {
        $this->scopeCheck(['admin']);
        $clients = $this->clientRepository->findAll();

        if (count($clients) == 0) {
            $this->sendJsonResponse(['No clients found']);
        }

        $this->sendJsonObjectResponse($clients);
    }

    public function init()
    {
        parent::init();
        $container = ContainerService::getInstance()->getContainer();
        $this->clientRepository = $container['repository.Client'];
    }
}