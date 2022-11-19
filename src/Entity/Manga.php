<?php

namespace App\Entity;

use App\Repository\MangaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MangaRepository::class)]
class Manga
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subMangas')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $subMangas;

    #[ORM\ManyToMany(targetEntity: Character::class, mappedBy: 'manga')]
    private Collection $characters;

    public function __construct()
    {
        $this->subMangas = new ArrayCollection();
        $this->characters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubMangas(): Collection
    {
        return $this->subMangas;
    }

    public function addSubManga(self $subManga): self
    {
        if (!$this->subMangas->contains($subManga)) {
            $this->subMangas->add($subManga);
            $subManga->setParent($this);
        }

        return $this;
    }

    public function removeSubManga(self $subManga): self
    {
        if ($this->subMangas->removeElement($subManga)) {
            // set the owning side to null (unless already changed)
            if ($subManga->getParent() === $this) {
                $subManga->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
            $character->addManga($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->removeElement($character)) {
            $character->removeManga($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getLabel();
    }
}
