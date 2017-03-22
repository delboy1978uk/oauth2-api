<?php

namespace OAuth;

use DateTime;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;

/**
 * @Entity(repositoryClass="OAuth\Repository\RefreshTokenRepository")
 * @Table(name="RefreshToken")
 */
class RefreshToken implements RefreshTokenEntityInterface
{
    use RefreshTokenTrait;

    /**
     * @var string
     * @Id
     * @Column(type="string", length=40)
     */
    protected $identifier;

    /**
     * @var AccessTokenEntityInterface
     * @OneToOne(targetEntity="OAuth\AccessToken")
     * @JoinColumn(name="accessToken", referencedColumnName="identifier")
     */
    protected $accessToken;

    /**
     * @var DateTime
     * @Column(type="date",nullable=true)
     */
    protected $expiryDateTime;

    /**
     * {@inheritdoc}
     */
    public function setAccessToken(AccessTokenEntityInterface $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        return $this->accessToken;
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
}