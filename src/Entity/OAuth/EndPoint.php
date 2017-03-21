<?php

namespace OAuth;

/**
 * @Entity
 * @Table(name="Endpoint")
 */
class EndPoint
{
    /**
     * @var int $id
     * @Id
     * @Column(type="integer",length=11)
     * @GeneratedValue
     */
    private $id;

    /**
     * @var string $clientId
     * @ManyToOne(targetEntity="OAuth\Client", inversedBy="endPoints")
     * @JoinColumn(onDelete="CASCADE")
     */
    private $clientId;

    /**
     * @var string $redirectUri
     * @Column(type="string",length=255,nullable=true)
     */
    private $redirectUri;

    public function __construct()
    {
        $this->clientId = '';
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
     * @return EndPoint
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
     * @return EndPoint
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
     * @return EndPoint
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }
}