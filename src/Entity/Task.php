<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Server::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private $server;

    #[ORM\Column(type: 'text')]
    private $task;

    #[ORM\Column(type: 'string', length: 255)]
    private $complete_on;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTask(): ?string
    {
        return $this->task;
    }

    public function setTask(string $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getCompleteOn(): ?string
    {
        return $this->complete_on;
    }

    public function setCompleteOn(string $complete_on): self
    {
        $this->complete_on = $complete_on;

        return $this;
    }
}
