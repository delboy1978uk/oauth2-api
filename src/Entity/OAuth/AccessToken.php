<?php

namespace OAuth;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;

/**
* @ORM\Entity(repositoryClass="OAuth\Repository\AccessTokenRepository")
* @ORM\Table(name="AccessToken")
*/
class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait;

    /**
     * @var ArrayCollection $scopes
     * @ORM\ManyToMany(targetEntity="Scope", inversedBy="accessTokens")
     * @ORM\JoinTable(name="AccessToken_Scope",
     *      joinColumns={@ORM\JoinColumn(name="token_id", referencedColumnName="identifier")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="scope_id", referencedColumnName="identifier")}
     *      )
     */
    protected $scopes;

    /**
     * @var DateTime
     * @ORM\Column(type="date",nullable=true)
     */
    protected $expiryDateTime;

    /**
     * @var int
     * @ORM\Column(type="integer", length=11)
     */
    protected $userIdentifier;

    /**
     * @var ClientEntityInterface
     * @ORM\ManyToOne(targetEntity="OAuth\Client")
     * @ORM\JoinColumn(name="client", referencedColumnName="id")
     */
    protected $client;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string", length=40)
     */
    protected $identifier;

    public function __construct()
    {
        $this->scopes = new ArrayCollection();
    }

    /**
     * Set token
     *
     * @param string $token
     * @return AccessToken
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get token
     *
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