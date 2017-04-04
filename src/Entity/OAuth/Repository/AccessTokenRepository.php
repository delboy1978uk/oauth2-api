<?php

namespace OAuth\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;
use OAuth2\Storage\AccessTokenInterface;
use OAuth\AccessToken;

class AccessTokenRepository extends EntityRepository implements AccessTokenInterface
{
    /**
     * @param $oauthToken
     * @return null|array
     */
    public function getAccessToken($oauthToken)
    {
        /** @var AccessToken $token */
        $token = $this->findOneBy(['token' => $oauthToken]);
        if ($token instanceof AccessToken) {
            $array = (array) $token->toArray();
            /** @var DateTime $date */
            $date = $array['expires'];
            $array['expires'] = $date->getTimestamp();
            return $array;
        }
        return null;
    }

    /**
     * @param string $oauthToken
     * @param string $clientId
     * @param string $userEmail
     * @param int $expires
     * @param null $scope
     */
    public function setAccessToken($oauthToken, $clientIdentifier, $userEmail, $expires, $scope = null)
    {
        $client = $this->_em->getRepository('OAuth\Client')->findOneBy(['clientIdentifier' => $clientIdentifier]);
        if ($userEmail) {
            $user = $this->_em->getRepository('OAuth\User')->findOneBy(['email' => $userEmail]);
        } else {
            $user = null; // is this required? guess we'll find out!
        }
        $token = AccessToken::fromArray([
            'token'     => $oauthToken,
            'client'    => $client,
            'user'      => $user,
            'expires'   => (new DateTime())->setTimestamp($expires),
            'scope'     => $scope,
        ]);
        $this->_em->persist($token);
        $this->_em->flush();
    }

}