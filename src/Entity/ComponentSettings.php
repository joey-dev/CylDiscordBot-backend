<?php

namespace App\Entity;

use App\Repository\ComponentSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComponentSettingsRepository::class)]
class ComponentSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Component::class, inversedBy: 'componentSettings')]
    #[ORM\JoinColumn(nullable: false)]
    private $component;

    #[ORM\ManyToOne(targetEntity: Server::class, inversedBy: 'componentSettings')]
    #[ORM\JoinColumn(nullable: false)]
    private $server;

    #[ORM\Column(type: 'smallint')]
    private $turned_on;

    #[ORM\Column(type: 'text')]
    private $data;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComponent(): ?Component
    {
        return $this->component;
    }

    public function setComponent(?Component $component): self
    {
        $this->component = $component;

        return $this;
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function getTurnedOn(): ?int
    {
        return $this->turned_on;
    }

    public function setTurnedOn(int $turned_on): self
    {
        $this->turned_on = $turned_on;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }
}
