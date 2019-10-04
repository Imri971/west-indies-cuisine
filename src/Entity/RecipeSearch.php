<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeSearchRepository")
 */
class RecipeSearch
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tags;

    /**
     * @ORM\Column(type="integer")
     */
    private $preparationTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $difficultyLevel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ingredients;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priceRange;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecipeLike", mappedBy="recipe")
     */
    private $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getPreparationTime(): ?int
    {
        return $this->preparationTime;
    }

    public function setPreparationTime(int $preparationTime): self
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    public function getDifficultyLevel(): ?int
    {
        return $this->difficultyLevel;
    }

    public function setDifficultyLevel(int $difficultyLevel): self
    {
        $this->difficultyLevel = $difficultyLevel;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getPriceRange(): ?int
    {
        return $this->priceRange;
    }

    public function setPriceRange(?int $priceRange): self
    {
        $this->priceRange = $priceRange;

        return $this;
    }

    /**
     * @return Collection|RecipeLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(RecipeLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setRecipe($this);
        }

        return $this;
    }

    public function removeLike(RecipeLike $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getRecipe() === $this) {
                $like->setRecipe(null);
            }
        }

        return $this;
    }
}