<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\InheritanceType(value: 'JOINED')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap([
    'article' => ItemArticle::class,
    'category' => ItemCategory::class,
    'media' => ItemMedia::class,
    'url' => ItemURL::class,
    'vote' => ItemVote::class
])]
abstract class Item
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private ?string $icon = null;

    #[ORM\ManyToMany(targetEntity: Screen::class, mappedBy: 'items')]
    #[MaxDepth(1)]
    private Collection $screens;

    #[ORM\ManyToMany(targetEntity: ItemCategory::class, mappedBy: 'children', cascade: ['persist'])]
    #[MaxDepth(1)]
    private Collection $categories;

    public function __construct()
    {
        $this->screens = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function getScreens(): Collection
    {
        return $this->screens;
    }

    public function setScreens(Collection $screens): self
    {
        $this->screens = $screens;
        return $this;
    }

    public function addScreen(Screen $screen): self
    {
        $this->screens->add($screen);
        $screen->addItem($this);
        return $this;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Collection $categories): self
    {
        $this->categories = $categories;
        return $this;
    }

    public function addCategory(ItemCategory $category): self
    {
        $this->categories->add($category);
        $category->addChild($this);
        return $this;
    }

    public function getType(): string
    {
        return [
            ItemArticle::class => 'article',
            ItemCategory::class => 'category',
            ItemMedia::class => 'media',
            ItemURL::class => 'url',
            ItemVote::class => 'vote',
        ][get_class($this)];
    }
}