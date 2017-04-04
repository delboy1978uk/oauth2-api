<?php

namespace OAuth;

use DateTime;

/**
 * @Entity(repositoryClass="OAuth\Repository\AuthCodeRepository")
 * @Table(name="AuthCode",uniqueConstraints={@UniqueConstraint(name="code_idx", columns={"code"})})
 */
class AuthCode
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
    private $code;

    /**
     * @var int
     * @Column(type="integer",length=11)
     */
    private $clientId;

    /**
     * @var string
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
     * @Column(type="string",length=255)
     */
    private $redirectUri;

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
     * Set code
     *
     * @param string $code
     * @return AuthCode
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set client_id
     *
     * @param int $clientId
     * @return AuthCode
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
     * @param string $userId
     * @return AuthCode
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Get user_identifier
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set expires
     *
     * @param \DateTime $expires
     * @return AuthCode
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * Get expires
     *
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set redirect_uri
     *
     * @param string $redirectUri
     * @return AuthCode
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

    /**
     * Set scope
     *
     * @param string $scope
     * @return AuthCode
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
     * @return AuthCode
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
     * @return AuthCode
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
            'code' => $this->code,
            'client_id' => $this->clientId,
            'user_id' => $this->userId,
            'expires' => $this->expires,
            'scope' => $this->scope,
        ];
    }

    /**
     * @param $params
     * @return AuthCode
     */
    public static function fromArray($params)
    {
        $code = new self();
        foreach ($params as $property => $value) {
            $code->$property = $value;
        }
        return $code;
    }
}