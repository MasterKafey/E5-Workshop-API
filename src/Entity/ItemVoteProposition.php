<?php

namespace App\Entity;

use App\Repository\ItemVotePropositionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemVotePropositionRepository::class)]
class ItemVoteProposition
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $text = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $score = 0;

    #[ORM\ManyToOne(targetEntity: ItemVote::class, inversedBy: 'propositions')]
    private ItemVote $item;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function getItem(): ItemVote
    {
        return $this->item;
    }

    public function setItem(ItemVote $item): self
    {
        $this->item = $item;
        return $this;
    }
}