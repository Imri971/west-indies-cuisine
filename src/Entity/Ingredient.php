<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IngredientRepository")
 */
class Ingredient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Unit", inversedBy="ingredients")
     */
    private $units;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Recipe", mappedBy="ingredients")
     */
    private $recipes;

    

    public function __construct()
    {
        $this->unit = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->recipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection|Unit[]
     */
    public function getUnit(): Collection
    {
        return $this->unit;
    }

    public function addUnit(Unit $unit): self
    {
        if (!$this->unit->contains($unit)) {
            $this->unit[] = $unit;
            $unit->setIngredient($this);
        }

        return $this;
    }

    public function removeUnit(Unit $unit): self
    {
        if ($this->unit->contains($unit)) {
            $this->unit->removeElement($unit);
            // set the owning side to null (unless already changed)
            if ($unit->getIngredient() === $this) {
                $unit->setIngredient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Unit[]
     */
    public function getUnits(): Collection
    {
        return $this->units;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->addIngredient($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->contains($recipe)) {
            $this->recipes->removeElement($recipe);
            $recipe->removeIngredient($this);
        }

        return $this;
    }

    public function __toString()
    {
        
        return $this->name;
    }
}
