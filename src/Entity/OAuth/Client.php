<?php

namespace OAuth;

use Doctrine\Common\Collections\ArrayCollection;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * @Entity
 * @Table(name="Client")
 */
class Client implements ClientEntityInterface
{
    /**
     * @var string $id
     * @Id
     * @Column(type="string",length=40)
     * @GeneratedValue
     */
    private $id;

    /**
     * @var string $secret
     * @Column(type="string",length=40)
     */
    private $secret;

    /**
     * @var string $name
     * @Column(type="string",length=255)
     */
    private $name;

    /**
     * @var bool $autoApprove
     * @Column(type="boolean")
     */
    private $autoApprove = false;

    /**
     * @var ArrayCollection $endPoints
     * @OneToMany(targetEntity="OAuth\EndPoint", mappedBy="clientId")
     */
    private $endPoints;

    public function __construct()
    {
        $this->endPoints = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Client
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     * @return Client
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @return bool
     */
    public function isAutoApprove()
    {
        return $this->autoApprove;
    }

    /**
     * @param bool $autoApprove
     * @return Client
     */
    public function setAutoApprove($autoApprove)
    {
        $this->autoApprove = $autoApprove;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndPoints()
    {
        return $this->endPoints;
    }

    /**
     * @param mixed $endPoints
     * @return Client
     */
    public function setEndPoints($endPoints)
    {
        $this->endPoints = $endPoints;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        // TODO: Implement getIdentifier() method.
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        // TODO: Implement getRedirectUri() method.
        return '/blah';
    }


}