<?php

namespace App\Entity;

use App\Repository\ComponentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComponentRepository::class)]
class Component
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Plugin::class, inversedBy: 'components')]
    private $plugin;

    #[ORM\Column(type: 'text')]
    private $data;

    #[ORM\Column(type: 'integer')]
    private $order_id;

    #[ORM\OneToMany(mappedBy: 'component', targetEntity: ComponentSettings::class)]
    private $componentSettings;

    #[ORM\ManyToOne(targetEntity: ComponentType::class, inversedBy: 'components')]
    #[ORM\JoinColumn(nullable: false)]
    private $type;

    public function __construct()
    {
        $this->componentSettings = new ArrayCollection();
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

    public function getPlugin(): ?Plugin
    {
        return $this->plugin;
    }

    public function setPlugin(?Plugin $plugin): self
    {
        $this->plugin = $plugin;

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
            $componentSetting->setComponent($this);
        }

        return $this;
    }

    public function removeComponentSetting(ComponentSettings $componentSetting): self
    {
        if ($this->componentSettings->removeElement($componentSetting)) {
            // set the owning side to null (unless already changed)
            if ($componentSetting->getComponent() === $this) {
                $componentSetting->setComponent(null);
            }
        }

        return $this;
    }

    public function getType(): ?ComponentType
    {
        return $this->type;
    }

    public function setType(?ComponentType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
