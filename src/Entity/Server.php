<?php

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServerRepository::class)]
class Server
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $server_id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 10)]
    private $command_prefix;

    #[ORM\OneToMany(mappedBy: 'server', targetEntity: Role::class, orphanRemoval: true)]
    private $roles;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'server')]
    private $users;

    #[ORM\ManyToOne(targetEntity: Language::class, inversedBy: 'servers')]
    #[ORM\JoinColumn(nullable: true)]
    private $language;

    #[ORM\OneToMany(mappedBy: 'server', targetEntity: PluginSettings::class)]
    private $pluginSettings;

    #[ORM\OneToMany(mappedBy: 'server', targetEntity: ComponentSettings::class)]
    private $componentSettings;

    #[ORM\OneToMany(mappedBy: 'server', targetEntity: Channel::class, orphanRemoval: true)]
    private $channels;

    #[ORM\OneToMany(mappedBy: 'server', targetEntity: Emote::class, orphanRemoval: true)]
    private $emotes;

    #[ORM\OneToMany(mappedBy: 'server', targetEntity: Task::class, orphanRemoval: true)]
    private $tasks;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->pluginSettings = new ArrayCollection();
        $this->componentSettings = new ArrayCollection();
        $this->channels = new ArrayCollection();
        $this->emotes = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServerId(): ?string
    {
        return $this->server_id;
    }

    public function setServerId(string $server_id): self
    {
        $this->server_id = $server_id;

        return $this;
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

    public function getCommandPrefix(): ?string
    {
        return $this->command_prefix;
    }

    public function setCommandPrefix(string $command_prefix): self
    {
        $this->command_prefix = $command_prefix;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->setServer($this);
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->removeElement($role)) {
            // set the owning side to null (unless already changed)
            if ($role->getServer() === $this) {
                $role->setServer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addServer($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeServer($this);
        }

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Collection|PluginSettings[]
     */
    public function getPluginSettings(): Collection
    {
        return $this->pluginSettings;
    }

    public function addPluginSetting(PluginSettings $pluginSetting): self
    {
        if (!$this->pluginSettings->contains($pluginSetting)) {
            $this->pluginSettings[] = $pluginSetting;
            $pluginSetting->setServer($this);
        }

        return $this;
    }

    public function removePluginSetting(PluginSettings $pluginSetting): self
    {
        if ($this->pluginSettings->removeElement($pluginSetting)) {
            // set the owning side to null (unless already changed)
            if ($pluginSetting->getServer() === $this) {
                $pluginSetting->setServer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ComponentSettings[]
     */
    public function getComponentSettings(): Collection
    {
        return $this->componentSettings;
    }

    public function addComponentSetting(ComponentSettings $componentSetting): self
    {
        if (!$this->componentSettings->contains($componentSetting)) {
            $this->componentSettings[] = $componentSetting;
            $componentSetting->setServer($this);
        }

        return $this;
    }

    public function removeComponentSetting(ComponentSettings $componentSetting): self
    {
        if ($this->componentSettings->removeElement($componentSetting)) {
            // set the owning side to null (unless already changed)
            if ($componentSetting->getServer() === $this) {
                $componentSetting->setServer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Channel[]
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(Channel $channel): self
    {
        if (!$this->channels->contains($channel)) {
            $this->channels[] = $channel;
            $channel->setServer($this);
        }

        return $this;
    }

    public function removeChannel(Channel $channel): self
    {
        if ($this->channels->removeElement($channel)) {
            // set the owning side to null (unless already changed)
            if ($channel->getServer() === $this) {
                $channel->setServer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Emote[]
     */
    public function getEmotes(): Collection
    {
        return $this->emotes;
    }

    public function addEmote(Emote $emote): self
    {
        if (!$this->emotes->contains($emote)) {
            $this->emotes[] = $emote;
            $emote->setServer($this);
        }

        return $this;
    }

    public function removeEmote(Emote $emote): self
    {
        if ($this->emotes->removeElement($emote)) {
            // set the owning side to null (unless already changed)
            if ($emote->getServer() === $this) {
                $emote->setServer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setServer($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getServer() === $this) {
                $task->setServer(null);
            }
        }

        return $this;
    }
}
