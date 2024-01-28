<?php

namespace App\Form\Model\Item\Vote;

class CreateItemVoteModel
{
    private ?string $name = null;

    private ?string $icon = null;

    private ?string $text = null;

    private array $propositions = [];

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function getPropositions(): array
    {
        return $this->propositions;
    }

    public function setPropositions(array $propositions): self
    {
        $this->propositions = $propositions;
        return $this;
    }
}