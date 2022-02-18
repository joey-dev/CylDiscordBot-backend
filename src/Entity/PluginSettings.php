<?php

namespace App\Entity;

use App\Repository\PluginSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PluginSettingsRepository::class)]
class PluginSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Plugin::class, inversedBy: 'pluginSettings')]
    #[ORM\JoinColumn(nullable: false)]
    private $plugin;

    #[ORM\ManyToOne(targetEntity: Server::class, inversedBy: 'pluginSettings')]
    #[ORM\JoinColumn(nullable: false)]
    private $server;

    #[ORM\Column(type: 'smallint')]
    private $turned_on;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlugin(): ?Plugin
    {
        return $this->plugin;
    }

    public function setPlugin(?Plugin $plugin): self
    {
        $this->plugin = $plugin;

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
}
