<?php

namespace OAuth\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;
use OAuth\AuthCode;
use OAuth2\Storage\AuthorizationCodeInterface;

class AuthCodeRepository extends EntityRepository implements AuthorizationCodeInterface
{
    /**
     * @param $code
     * @return null|array
     */
    public function getAuthorizationCode($code)
    {
        $authCode = $this->findOneBy(['code' => $code]);
        if ($authCode) {
            $authCode = $authCode->toArray();
            /** @var DateTime $date */
            $date = $authCode['expires'];
            $authCode['expires'] = $date->getTimestamp();
            return $authCode;
        }
        return null;
    }

    /**
     * @param $code
     * @param $clientIdentifier
     * @param $userEmail
     * @param $redirectUri
     * @param $expires
     * @param null $scope
     */
    public function setAuthorizationCode($code, $clientIdentifier, $userEmail, $redirectUri, $expires, $scope = null)
    {
        $client = $this->_em->getRepository('YourNamespace\Entity\OAuthClient')->findOneBy(array('client_identifier' => $clientIdentifier));
        $user = $this->_em->getRepository('YourNamespace\Entity\OAuthUser')->findOneBy(['email' => $userEmail]);
        $date = new DateTime();
        $date->setTimestamp($expires);
        $authCode = AuthCode::fromArray([
            'code'           => $code,
            'client'         => $client,
            'user'           => $user,
            'redirect_uri'   => $redirectUri,
            'expires'        => $date,
            'scope'          => $scope,
        ]);
        $this->_em->persist($authCode);
        $this->_em->flush();
    }

    /**
     * @param $code
     */
    public function expireAuthorizationCode($code)
    {
        $authCode = $this->findOneBy(['code' => $code]);
        $this->_em->remove($authCode);
        $this->_em->flush();
    }
}