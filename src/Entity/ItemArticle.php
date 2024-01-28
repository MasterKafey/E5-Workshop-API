<?php

namespace App\Entity;

use App\Repository\ItemArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemArticleRepository::class)]
class ItemArticle extends Item
{
    #[ORM\Column(type: Types::STRING)]
    private ?string $content = null;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }
}