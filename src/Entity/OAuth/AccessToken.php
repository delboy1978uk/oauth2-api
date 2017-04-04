<?php

namespace OAuth;

use DateTime;

/**
* @Entity(repositoryClass="OAuth\Repository\AccessTokenRepository")
* @Table(name="AccessToken",uniqueConstraints={@UniqueConstraint(name="token_idx", columns={"token"})})
*/
class AccessToken
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
    private $token;

    /**
     * @var int $clientId
     * @Column(type="integer", length=11)
     */
    private $clientId;

    /**
     * @var int $userId
     * @Column(type="integer", length=11, nullable=true)
     */
    private $userId;

    /**
     * @var DateTime
     * @Column(type="datetime")
     */
    private $expires;

    /**
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    private $scope;

    /**
     * @var Client
     * @ManyToOne(targetEntity="OAuth\Client")
     * @JoinColumn(name="clientId", referencedColumnName="id")
     */
    private $client;

    /**
     * @var User
     * @ManyToOne(targetEntity="OAuth\User")
     * @JoinColumn(name="userId", referencedColumnName="id")
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
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set client_id
     *
     * @param int $clientId
     * @return AccessToken
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
     * @return AccessToken
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
     * @return AccessToken
     */
    public function setExpires($expires)
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
     * @return AccessToken
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
     * @return AccessToken
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
     * @param $params
     * @return AccessToken
     */
    public static function fromArray($params)
    {
        $token = new self();
        foreach ($params as $property => $value) {
            $token->$property = $value;
        }
        return $token;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return AccessToken
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
            'token' => $this->token,
            'client_id' => $this->clientId,
            'user_id' => $this->userId,
            'expires' => $this->expires,
            'scope' => $this->scope,
        ];
    }
}