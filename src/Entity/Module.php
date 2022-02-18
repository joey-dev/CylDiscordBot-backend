<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'integer')]
    private $order_id;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: Plugin::class)]
    private $plugins;

    public function __construct()
    {
        $this->plugins = new ArrayCollection();
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
     * @return Collection|Plugin[]
     */
    public function getPlugins(): Collection
    {
        return $this->plugins;
    }

    public function addPlugin(Plugin $plugin): self
    {
        if (!$this->plugins->contains($plugin)) {
            $this->plugins[] = $plugin;
            $plugin->setModule($this);
        }

        return $this;
    }

    public function removePlugin(Plugin $plugin): self
    {
        if ($this->plugins->removeElement($plugin)) {
            // set the owning side to null (unless already changed)
            if ($plugin->getModule() === $this) {
                $plugin->setModule(null);
            }
        }

        return $this;
    }
}
