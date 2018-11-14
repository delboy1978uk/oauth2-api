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
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int|null
     */
    private $id;

    /**
     * @var string $identifier
     * @ORM\Column(type="string", length=40)
     */
    protected $identifier;

    /**
     * @var string $description
     * @ORM\Column(type="string", length=255)
     */
    private $description;

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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->getIdentifier();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}