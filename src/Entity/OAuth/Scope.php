<?php

namespace OAuth;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
* @Entity
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

    /**
     * @return mixed
     */
    function jsonSerialize()
    {
        return $this->getIdentifier();
    }
}