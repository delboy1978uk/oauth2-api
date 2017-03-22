<?php

namespace OAuth;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
* @Entity(repositoryClass="OAuth\Repository\ScopeRepository")
* @Table(name="Scope")
*/
class Scope implements ScopeEntityInterface
{
    /**
     * @var string
     * @Id
     * @Column(type="string", length=40)
     */
    protected $identifier;

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