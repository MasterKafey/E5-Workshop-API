<?php

namespace App\Entity;

use App\Repository\ItemCategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemCategoryRepository::class)]
class ItemCategory extends Item
{
    #[ORM\ManyToMany(targetEntity: Item::class, inversedBy: 'categories')]
    private Collection $children;

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setChildren(Collection $children): self
    {
        $this->children = $children;
        return $this;
    }

    public function addChild(Item $item): self
    {
        $this->children->add($item);
        return $this;
    }
}