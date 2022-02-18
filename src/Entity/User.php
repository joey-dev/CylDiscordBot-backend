<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $token;

    #[ORM\ManyToMany(targetEntity: Server::class, inversedBy: 'users')]
    private $server;

    #[ORM\Column(type: 'string', length: 25)]
    private $user_id;

    #[ORM\Column(type: 'string', length: 255)]
    private $token_expires_in;

    #[ORM\Column(type: 'string', length: 255)]
    private $refresh_token;

    public function __construct()
    {
        $this->server = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection|Server[]
     */
    public function getServer(): Collection
    {
        return $this->server;
    }

    public function addServer(Server $server): self
    {
        if (!$this->server->contains($server)) {
            $this->server[] = $server;
        }

        return $this;
    }

    public function removeServer(Server $server): self
    {
        $this->server->removeElement($server);

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->user_id;
    }

    public function setUserId(string $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getTokenExpiresIn(): ?string
    {
        return $this->token_expires_in;
    }

    public function setTokenExpiresIn(string $token_expires_in): self
    {
        $this->token_expires_in = $token_expires_in;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refresh_token;
    }

    public function setRefreshToken(string $refresh_token): self
    {
        $this->refresh_token = $refresh_token;

        return $this;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials()
    {

    }

    public function getUserIdentifier(): string
    {
        return $this->getUserId();
    }
}
