<?php
namespace OAuth;

use Del\Common\Container\RegistrationInterface;
use Doctrine\ORM\EntityManager;
use OAuth\Service\ClientService;
use Pimple\Container;

class OAuthPackage implements RegistrationInterface
{
    /**
     * @param Container $c
     */
    public function addToContainer(Container $c)
    {
        // AccessToken
        $function = function ($c) {
            /** @var EntityManager $entityManager */
            $entityManager = $c['doctrine.entity_manager'];
            $repository = $entityManager->getRepository(AccessToken::class);
            return $repository;
        };
        $c['repository.AccessToken'] = $c->factory($function);

        // AuthCode
        $function = function ($c) {
            /** @var EntityManager $entityManager */
            $entityManager = $c['doctrine.entity_manager'];
            $repository = $entityManager->getRepository(AuthCode::class);
            return $repository;
        };
        $c['repository.AuthCode'] = $c->factory($function);

        // Client
        $function = function ($c) {
            /** @var EntityManager $entityManager */
            $entityManager = $c['doctrine.entity_manager'];
            $repository = $entityManager->getRepository(Client::class);
            return $repository;
        };
        $c['repository.Client'] = $c->factory($function);

        $function = function ($c) {
            $repository = $c['repository.Client'];
            $svc = new ClientService($repository);
            return $svc;
        };
        $c['oauth.service.client'] = $c->factory($function);

        // RefreshToken
        $function = function ($c) {
            /** @var EntityManager $entityManager */
            $entityManager = $c['doctrine.entity_manager'];
            $repository = $entityManager->getRepository(RefreshToken::class);
            return $repository;
        };
        $c['repository.RefreshToken'] = $c->factory($function);

        // Scope
        $function = function ($c) {
            /** @var EntityManager $entityManager */
            $entityManager = $c['doctrine.entity_manager'];
            $repository = $entityManager->getRepository(Scope::class);
            return $repository;
        };
        $c['repository.Scope'] = $c->factory($function);

        // User
        $function = function ($c) {
            /** @var EntityManager $entityManager */
            $entityManager = $c['doctrine.entity_manager'];
            $repository = $entityManager->getRepository(OAuthUser::class);
            return $repository;
        };
        $c['repository.User'] = $c->factory($function);
    }

    public function getEntityPath()
    {
        return 'src/Entity/OAuth';
    }

    public function hasEntityPath()
    {
        return true;
    }

}