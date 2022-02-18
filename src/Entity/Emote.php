<?php

namespace App\Entity;

use App\Repository\EmoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmoteRepository::class)]
class Emote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $emote_id;

    #[ORM\ManyToOne(targetEntity: Server::class, inversedBy: 'emotes')]
    #[ORM\JoinColumn(nullable: false)]
    private $server;

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

    public function getEmoteId(): ?string
    {
        return $this->emote_id;
    }

    public function setEmoteId(string $emote_id): self
    {
        $this->emote_id = $emote_id;

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
}
