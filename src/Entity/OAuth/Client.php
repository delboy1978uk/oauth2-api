<?php

namespace OAuth;

use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * @Entity(repositoryClass="OAuth\Repository\ClientRepository")
 * @Table(name="Client")
 */
class Client implements ClientEntityInterface
{
    /**
     * @var string
     * @Column(type="string", length=40)
     */
    private $name;

    /**
     * @var string|string[]
     * @Column(type="string", length=255)
     */
    private $redirectUri;

    /**
     * @var string
     * @Id
     * @Column(type="string", length=40)
     */
    private $identifier;

    /**
     * @var string
     * @Column(type="string", length=40, nullable=true)
     */
    private $secret;

    /**
     * @var bool $confidential
     */
    private $confidential;

    /**
     * @var User $user*
     * @ManyToOne(targetEntity="OAuth\User")
     * @JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Get the client's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the registered redirect URI (as a string).
     *
     * Alternatively return an indexed array of redirect URIs.
     *
     * @return string|string[]
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @param string $name
     * @return Client
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string|\string[] $redirectUri
     * @return Client
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     * @return Client
     */
    public function setSecret(string $secret): Client
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConfidential(): bool
    {
        return $this->confidential;
    }

    /**
     * @param bool $confidential
     * @return Client
     */
    public function setConfidential(bool $confidential): Client
    {
        $this->confidential = $confidential;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Client
     */
    public function setUser(User $user): Client
    {
        $this->user = $user;
        return $this;
    }
}