<?php

namespace App\Entity;

use App\Repository\ItemArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemArticleRepository::class)]
class ItemArticle extends Item
{
    #[ORM\Column(type: Types::STRING)]
    private ?string $content = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ItemArticleComment::class)]
    private Collection $comments;

    public function __construct()
    {
        parent::__construct();
        $this->comments = new ArrayCollection();
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function setComments(Collection $comments): self
    {
        $this->comments = $comments;
        return $this;
    }
}