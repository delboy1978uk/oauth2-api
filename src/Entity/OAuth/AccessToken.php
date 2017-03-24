<?php

namespace OAuth;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;

/**
* @Entity(repositoryClass="OAuth\Repository\AccessTokenRepository")
* @Table(name="AccessToken")
*/
class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait;

    /**
     * @var ArrayCollection $scopes
     * @ManyToMany(targetEntity="OAuth\Scope")
     * @JoinTable(name="AccessTokenScope",
     *      joinColumns={@JoinColumn(name="scopeId", referencedColumnName="identifier")},
     *      inverseJoinColumns={@JoinColumn(name="accessTokenId", referencedColumnName="identifier")}
     *      )
     */
    protected $scopes;

    /**
     * @var DateTime
     * @Column(type="date",nullable=true)
     */
    protected $expiryDateTime;

    /**
     * @var User
     * @Column(type="integer", length=11)
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
     * @param ScopeEntityInterface $scope
     * @return $this
     */
    public function addScope(ScopeEntityInterface $scope)
    {
        $this->scopes->add($scope);
        return $this;
    }

    /**
     * Return an array of scopes associated with the token.
     *
     * @return ScopeEntityInterface[]
     */
    public function getScopes()
    {
        return $this->scopes->toArray();
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
     * @param int $identifier
     * @return $this
     */
    public function setUserIdentifier($identifier)
    {
        $this->userIdentifier = $identifier;
        return $this;
    }

    /**
     * Get the token user's identifier.
     *
     * @return int
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
}