<?php

namespace App\Controller;

use Del\Common\ContainerService;
use OAuth\Client;
use OAuth\Repository\ClientRepository;

class ClientController extends ResourceServerController
{
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

        $this->sendJsonObjectResponse($clients);
    }
}