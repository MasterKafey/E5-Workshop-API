<?php

namespace App\Entity;

use App\Repository\ItemURLRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemURLRepository::class)]
class ItemURL extends Item
{
    #[ORM\Column(type: Types::STRING)]
    private ?string $url = null;

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;
        return $this;
    }
}