<?php

namespace App\Entity;

use App\Repository\ItemMediaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemMediaRepository::class)]
class ItemMedia extends Item
{
    #[ORM\OneToOne(targetEntity: File::class)]
    private ?File $file = null;

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;
        return $this;
    }
}