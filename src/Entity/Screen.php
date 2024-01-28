<?php

namespace App\Entity;

use App\Repository\ScreenRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScreenRepository::class)]
class Screen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?string $id = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $qrCodeKey = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Item::class, inversedBy: 'screens')]
    private Collection $items;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getQrCodeKey(): ?string
    {
        return $this->qrCodeKey;
    }

    public function setQrCodeKey(?string $qrCodeKey): self
    {
        $this->qrCodeKey = $qrCodeKey;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function setItems(Collection $items): self
    {
        $this->items = $items;
        return $this;
    }

    public function addItem(Item $item): self
    {
        $this->items->add($item);
        return $this;
    }

    public function removeItem(Item $item): self
    {
        $this->items->removeElement($item);
        return $this;
    }
}