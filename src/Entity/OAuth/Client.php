<?php

namespace OAuth;

use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * @Entity
 * @Table(name="Client")
 */
class Client implements ClientEntityInterface
{

    /**
     * @var string
     * @Column(type="string", length=40)
     */
    protected $name;

    /**
     * @var string|string[]
     * @Column(type="string", length=255)
     */
    protected $redirectUri;

    /**
     * @var string
     * @Id
     * @Column(type="string", length=40)
     */
    protected $identifier;

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
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
}