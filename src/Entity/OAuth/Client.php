<?php

namespace OAuth;

use Doctrine\Common\Collections\ArrayCollection;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * @Entity(repositoryClass="OAuth\Repository\ClientRepository")
 * @Table(name="Client",uniqueConstraints={@UniqueConstraint(name="indentifier_idx", columns={"identifier"})})
 */
class Client implements ClientEntityInterface
{

    /**
     * @Id
     * @var string
     * @Column(type="integer", length=11)
     */
    private $id;

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
     * @Column(type="boolean")
     */
    private $confidential;

    /**
     * @var ArrayCollection $scopes
     * @ManyToMany(targetEntity="Scope")
     * @JoinTable(name="Client_Scope",
     *      joinColumns={@JoinColumn(name="client_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="scope_id", referencedColumnName="identifier")}
     *      )
     */
    private $scopes;

    public function __construct()
    {
        $this->scopes = new ArrayCollection();
    }

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
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Client
     */
    public function setId(string $id): Client
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getScopes(): ArrayCollection
    {
        return $this->scopes;
    }

    /**
     * @param ArrayCollection $scopes
     * @return Client
     */
    public function setScopes(ArrayCollection $scopes): Client
    {
        $this->scopes = $scopes;
        return $this;
    }
}