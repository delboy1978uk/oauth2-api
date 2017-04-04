<?php

namespace OAuth;

use OAuth\Traits\EncryptableFieldEntity;
/**
 * OAuthClient
 * @entity(repositoryClass="OAuth\Repository\ClientRepository")
 * @Table(name="Client",uniqueConstraints={@UniqueConstraint(name="identifier_idx", columns={"clientIdentifier"})})
 */
class Client
{
    use EncryptableFieldEntity;

    /**
     * @var integer
     * @Id
     * @Column(type="integer", length=11)
     * @GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @Column(type="string",length=50)
     */
    private $clientIdentifier;

    /**
     * @var string
     * @Column(type="string",length=20)
     */
    private $clientSecret;

    /**
     * @var string
     * @Column(type="string")
     */
    private $redirectUri = '';

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set client_identifier
     *
     * @param string $clientIdentifier
     * @return OAuthClient
     */
    public function setClientIdentifier($clientIdentifier)
    {
        $this->clientIdentifier = $clientIdentifier;
        return $this;
    }

    /**
     * Get client_identifier
     *
     * @return string
     */
    public function getClientIdentifier()
    {
        return $this->clientIdentifier;
    }

    /**
     * Set client_secret
     *
     * @param string $clientSecret
     * @return OAuthClient
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $this->encryptField($clientSecret);
        return $this;
    }

    /**
     * Get client_secret
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param $clientSecret
     * @return bool
     */
    public function verifyClientSecret($clientSecret)
    {
        return $this->verifyEncryptedFieldValue($this->getClientSecret(), $clientSecret);
    }

    /**
     * Set redirect_uri
     *
     * @param string $redirectUri
     * @return OAuthClient
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }

    /**
     * Get redirect_uri
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    public function toArray()
    {
        return [
            'client_id' => $this->clientIdentifier,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
        ];
    }
}