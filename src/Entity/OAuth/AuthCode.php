<?php

namespace OAuth;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * @Entity
 * @Table(name="AuthCode")
 */
class AuthCode implements AuthCodeEntityInterface
{

    /**
     * @var null|string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $redirectUri;

    /**
     * @var ArrayCollection $scopes
     * @ManyToMany(targetEntity="OAuth\Scope")
     * @JoinTable(name="AuthTokenScope",
     *      joinColumns={@JoinColumn(name="scopeId", referencedColumnName="identifier")},
     *      inverseJoinColumns={@JoinColumn(name="authTokenId", referencedColumnName="identifier")})
     */
    protected $scopes;

    /**
     * @var DateTime
     * @Column(type="date",nullable=true)
     */
    protected $expiryDateTime;

    /**
     * @var User
     * @OneToOne(targetEntity="OAuth\User")
     * @JoinColumn(name="client", referencedColumnName="id")
     */
    protected $userIdentifier;

    /**
     * @var ClientEntityInterface
     * @ManyToOne(targetEntity="OAuth\Client")
     * @JoinColumn(name="client", referencedColumnName="identifier")
     */
    protected $client;

    /**
     * @var string
     * @Id
     * @Column(type="string", length=40)
     */
    protected $identifier;

    public function __construct()
    {
        $this->scopes = new ArrayCollection();
    }

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
     * Associate a scope with the token.
     *
     * @param ScopeEntityInterface $scope
     */
    public function addScope(ScopeEntityInterface $scope)
    {
        $this->scopes[$scope->getIdentifier()] = $scope;
    }

    /**
     * Return an array of scopes associated with the token.
     *
     * @return ScopeEntityInterface[]
     */
    public function getScopes()
    {
        return array_values($this->scopes->toArray());
    }

    /**
     * Get the token's expiry date time.
     *
     * @return DateTime
     */
    public function getExpiryDateTime()
    {
        return $this->expiryDateTime;
    }

    /**
     * Set the date time when the token expires.
     *
     * @param DateTime $dateTime
     */
    public function setExpiryDateTime(DateTime $dateTime)
    {
        $this->expiryDateTime = $dateTime;
    }

    /**
     * Set the identifier of the user associated with the token.
     *
     * @param string|int $identifier The identifier of the user
     */
    public function setUserIdentifier($identifier)
    {
        $this->userIdentifier = $identifier;
    }

    /**
     * Get the token user's identifier.
     *
     * @return string|int
     */
    public function getUserIdentifier()
    {
        return $this->userIdentifier;
    }

    /**
     * Get the client that the token was issued to.
     *
     * @return ClientEntityInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set the client that the token was issued to.
     *
     * @param ClientEntityInterface $client
     */
    public function setClient(ClientEntityInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @param string $uri
     */
    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
    }
}