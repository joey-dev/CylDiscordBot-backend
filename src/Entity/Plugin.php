<?php

namespace App\Entity;

use App\Repository\PluginRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PluginRepository::class)]
class Plugin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Module::class, inversedBy: 'plugins')]
    #[ORM\JoinColumn(nullable: false)]
    private $module;

    #[ORM\Column(type: 'integer')]
    private $order_id;

    #[ORM\OneToMany(mappedBy: 'plugin', targetEntity: Component::class)]
    private $components;

    #[ORM\OneToMany(mappedBy: 'plugin', targetEntity: PluginSettings::class)]
    private $pluginSettings;

    #[ORM\ManyToOne(targetEntity: PluginType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $type;

    public function __construct()
    {
        $this->components = new ArrayCollection();
        $this->pluginSettings = new ArrayCollection();
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

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getOrderId(): ?int
    {
        return $this->order_id;
    }

    public function setOrderId(int $order_id): self
    {
        $this->order_id = $order_id;

        return $this;
    }

    /**
     * @return Collection|Component[]
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(Component $component): self
    {
        if (!$this->components->contains($component)) {
            $this->components[] = $component;
            $component->setPlugin($this);
        }

        return $this;
    }

    public function removeComponent(Component $component): self
    {
        if ($this->components->removeElement($component)) {
            // set the owning side to null (unless already changed)
            if ($component->getPlugin() === $this) {
                $component->setPlugin(null);
            }
        }

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
            $pluginSetting->setPlugin($this);
        }

        return $this;
    }

    public function removePluginSetting(PluginSettings $pluginSetting): self
    {
        if ($this->pluginSettings->removeElement($pluginSetting)) {
            // set the owning side to null (unless already changed)
            if ($pluginSetting->getPlugin() === $this) {
                $pluginSetting->setPlugin(null);
            }
        }

        return $this;
    }

    public function getType(): ?PluginType
    {
        return $this->type;
    }

    public function setType(?PluginType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
