<?php

namespace OAuth;

use DateTime;

/**
 * @Entity(repositoryClass="OAuth\Repository\RefreshTokenRepository")
 * @Table(name="RefreshToken",uniqueConstraints={@UniqueConstraint(name="refresh_token_idx", columns={"refreshToken"})})
 */
class RefreshToken
{
    /**
     * @var integer
     * @Id
     * @Column(type="integer", length=11)
     * @GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @Column(type="string",length=40)
     */
    private $refreshToken;

    /**
     * @var int
     * @Column(type="integer",length=11)
     */
    private $clientId;

    /**
     * @var int
     * @Column(type="integer",length=11, nullable=true)
     */
    private $userId;

    /**
     * @var DateTime
     * @Column(type="datetime")
     */
    private $expires;

    /**
     * @var string
     * @Column(type="string",length=50)
     */
    private $scope;

    /**
     * @var Client
     * @ManyToOne(targetEntity="OAuth\Client")
     * @JoinColumn(name="client", referencedColumnName="id")
     */
    private $client;

    /**
     * @var User
     * @ManyToOne(targetEntity="OAuth\User")
     * @JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

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
     * Set refresh_token
     *
     * @param string $refresh_token
     * @return RefreshToken
     */
    public function setRefreshToken($refresh_token)
    {
        $this->refresh_token = $refresh_token;
        return $this;
    }

    /**
     * Get refresh_token
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Set client_id
     *
     * @param int $clientId
     * @return RefreshToken
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * Get client_id
     *
     * @return int
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set user_id
     *
     * @param int $userId
     * @return RefreshToken
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Get user_identifier
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set expires
     *
     * @param DateTime $expires
     * @return RefreshToken
     */
    public function setExpires(DateTime $expires)
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * Get expires
     *
     * @return DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set scope
     *
     * @param string $scope
     * @return RefreshToken
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set client
     *
     * @param Client $client
     * @return RefreshToken
     */
    public function setClient(Client $client = null)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return RefreshToken
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'refresh_token' => $this->refreshToken,
            'client_id' => $this->clientId,
            'user_id' => $this->userId,
            'expires' => $this->expires,
            'scope' => $this->scope,
        ];
    }

    /**
     * @param $params
     * @return RefreshToken
     */
    public static function fromArray($params)
    {
        $token = new self();
        foreach ($params as $property => $value) {
            $token->$property = $value;
        }
        return $token;
    }
}