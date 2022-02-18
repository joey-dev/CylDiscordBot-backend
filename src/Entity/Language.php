<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LanguageRepository::class)]
class Language
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 5)]
    private $small_name;

    #[ORM\OneToMany(mappedBy: 'language', targetEntity: Server::class)]
    private $servers;

    public function __construct()
    {
        $this->servers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSmallName(): ?string
    {
        return $this->small_name;
    }

    public function setSmallName(string $small_name): self
    {
        $this->small_name = $small_name;

        return $this;
    }

    /**
     * @return Collection|Server[]
     */
    public function getServers(): Collection
    {
        return $this->servers;
    }

    public function addServer(Server $server): self
    {
        if (!$this->servers->contains($server)) {
            $this->servers[] = $server;
            $server->setLanguage($this);
        }

        return $this;
    }

    public function removeServer(Server $server): self
    {
        if ($this->servers->removeElement($server)) {
            // set the owning side to null (unless already changed)
            if ($server->getLanguage() === $this) {
                $server->setLanguage(null);
            }
        }

        return $this;
    }
}
