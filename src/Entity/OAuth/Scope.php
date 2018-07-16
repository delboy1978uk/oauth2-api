<?php

namespace OAuth;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
* @ORM\Entity(repositoryClass="OAuth\Repository\ScopeRepository")
* @ORM\Table(name="Scope")
*/
class Scope implements ScopeEntityInterface
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string", length=40)
     */
    protected $identifier;

    /**
     * @var ArrayCollection $accessTokens
     * @ORM\ManyToMany(targetEntity="AccessToken", mappedBy="scopes")
     */
    protected $accessTokens;

    public function __construct()
    {
        $this->accessTokens = new ArrayCollection();
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
     * @return string
     */
    function jsonSerialize()
    {
        return $this->getIdentifier();
    }
}