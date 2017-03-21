<?php
namespace OAuth;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="Session")
 */
class Session
{
    const OWNER_TYPE_USER = 'user';
    const OWNER_TYPE_CLIENT = 'client';
    const STAGE_REQUESTED = 'requested';
    const STAGE_GRANTED = 'granted';

    /**
     * @var int $id
     * @Id
     * @Column(type="integer",length=11)
     * @GeneratedValue
     */
    private $id;

    /**
     * @var string $clientId
     * @Column(type="string",length=40)
     */
    private $clientId;

    /**
     * @var string $redirectUri
     * @Column(type="string",length=255,nullable=true)
     */
    private $redirectUri;

    /**
     * @var string $ownerType
     * @Column(type="string",length=6)
     */
    private $ownerType;

    /**
     * @var string $ownerId
     * @Column(type="string",length=255,nullable=true)
     */
    private $ownerId;

    /**
     * @var string $authCode
     * @Column(type="string",length=40,nullable=true)
     */
    private $authCode;

    /**
     * @var string $accessToken
     * @Column(type="string",length=40,nullable=true)
     */
    private $accessToken;

    /**
     * @var string $refreshToken
     * @Column(type="string",length=40,nullable=true)
     */
    private $refreshToken;

    /**
     * @var DateTime $accessTokenExpires
     * @Column(type="date",nullable=true)
     */
    private $accessTokenExpires;

    /**
     * @var string $stage
     * @Column(type="string",length=8)
     */
    private $stage;

    /**
     * @var DateTime $firstRequested
     * @Column(type="date")
     */
    private $firstRequested;

    /**
     * @var DateTime $lastUpdated
     * @Column(type="date")
     */
    private $lastUpdated;

    /**
     * @var ArrayCollection $scopes
     * @ManyToMany(targetEntity="OAuth\Scope", inversedBy="sessions")
     * @JoinTable(name="Session_Scope")
     */
    private $scopes;

    public function __construct()
    {
        $this->clientId = '';
        $this->redirectUri = '';
        $this->ownerType = 'user';
        $this->ownerId = '';
        $this->authCode = '';
        $this->accessToken = '';
        $this->refreshToken = '';
        $this->stage = 'requested';
        $this->scopes = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Session
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     * @return Session
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @param string $redirectUri
     * @return Session
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }

    /**
     * @return string
     */
    public function getOwnerType()
    {
        return $this->ownerType;
    }

    /**
     * @param string $ownerType
     * @return Session
     */
    public function setOwnerType($ownerType)
    {
        $this->ownerType = $ownerType;
        return $this;
    }

    /**
     * @return string
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * @param string $ownerId
     * @return Session
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * @param string $authCode
     * @return Session
     */
    public function setAuthCode($authCode)
    {
        $this->authCode = $authCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     * @return Session
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     * @return Session
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getAccessTokenExpires()
    {
        return $this->accessTokenExpires;
    }

    /**
     * @param DateTime $accessTokenExpires
     * @return Session
     */
    public function setAccessTokenExpires($accessTokenExpires)
    {
        $this->accessTokenExpires = $accessTokenExpires;
        return $this;
    }

    /**
     * @return string
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * @param string $stage
     * @return Session
     */
    public function setStage($stage)
    {
        $this->stage = $stage;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getFirstRequested()
    {
        return $this->firstRequested;
    }

    /**
     * @param DateTime $firstRequested
     * @return Session
     */
    public function setFirstRequested($firstRequested)
    {
        $this->firstRequested = $firstRequested;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * @param DateTime $lastUpdated
     * @return Session
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;
        return $this;
    }
}