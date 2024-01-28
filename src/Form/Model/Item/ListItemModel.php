<?php

namespace App\Form\Model\Item;

use App\Entity\ItemArticle;
use App\Entity\ItemCategory;
use App\Entity\ItemMedia;
use App\Entity\ItemURL;
use App\Entity\ItemVote;
use Symfony\Component\Validator\Constraints as Assert;

class ListItemModel
{
    #[Assert\Range(min: 1)]
    private int $page = 1;

    #[Assert\Range(min: 1, max: 500)]
    private int $max = 100;

    #[Assert\Choice(choices: [ItemArticle::class, ItemCategory::class, ItemMedia::class, ItemURL::class, ItemVote::class])]
    private ?string $type = null;

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function setMax(int $max): self
    {
        $this->max = $max;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }
}