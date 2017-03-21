<?php

namespace OAuth;

use Doctrine\Common\Collections\ArrayCollection;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

/**
 * @Entity
 * @Table(name="Scope")
 */
class Scope
{
    use EntityTrait;

    /**
     * @var int $id
     * @Id
     * @Column(type="integer",length=11)
     * @GeneratedValue
     */
    private $id;

    /**
     * @var string $scope
     * @Column(type="string",length=255)
     */
    private $scope;

    /**
     * @var string $name
     * @Column(type="string",length=255)
     */
    private $name;

    /**
     * @var string $description
     * @Column(type="string",length=255,nullable=true)
     */
    private $description;

    /**
     * @var ArrayCollection $sessions
     * @ManyToMany(targetEntity="OAuth\Session", mappedBy="scopes")
     */
    private $sessions;

    /**
     * Scope constructor.
     */
    public function __construct()
    {
        $this->description = '';
        $this->sessions = new ArrayCollection();
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
     * @return Scope
     */
    public function setId($id)
    {
        $this->id = $id;
        $this->setIdentifier($id);
        return $this;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     * @return Scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
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
     * @return Scope
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Scope
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}