<?php

namespace App\Entity;

use App\Repository\ItemVoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemVoteRepository::class)]
class ItemVote extends Item
{
    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\OneToMany(mappedBy: 'item', targetEntity: ItemVoteProposition::class, cascade: ['persist', 'remove'])]
    private Collection $propositions;

    public function __construct()
    {
        parent::__construct();
        $this->propositions = new ArrayCollection();
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

    public function getPropositions(): Collection
    {
        return $this->propositions;
    }

    public function setPropositions(Collection $propositions): self
    {
        $this->propositions = $propositions;
        return $this;
    }
}