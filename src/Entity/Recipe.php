<?php

namespace App\Entity;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\SerializationContext;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list","show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=130)
     * @Serializer\Groups({"list","show"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list","show"})
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"show"})
     */
    private $preparation_time;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"show"})
     */
    private $price_range;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"show"})
     */
    private $difficulty_level;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Groups({"list","show"})
     */
    private $description;

    

   

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reviews", mappedBy="recipe")
     */
    private $reviews;

    

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Steps", mappedBy="recipe",cascade={"persist"})
     * @Serializer\Groups({"show"})
     */
    private $steps;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="recipes")
     * @Serializer\Groups({"show"})
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ingredient", inversedBy="recipes")
     * @Serializer\Groups({"show"})
     */
    private $ingredients;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\KitchenTools", inversedBy="recipes")
     * @Serializer\Groups({"show"})
     */
    private $kitchen_tools;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="recipe")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecipeLike", mappedBy="recipes")
     */
    private $likes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recipes")
     */
    private $user;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->kitchen_tools = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPreparationTime(): ?int
    {
        return $this->preparation_time;
    }

    public function setPreparationTime(int $preparation_time): self
    {
        $this->preparation_time = $preparation_time;

        return $this;
    }

    public function getPriceRange(): ?int
    {
        return $this->price_range;
    }

    public function setPriceRange(int $price_range): self
    {
        $this->price_range = $price_range;

        return $this;
    }

    public function getDifficultyLevel(): ?int
    {
        return $this->difficulty_level;
    }

    public function setDifficultyLevel(int $difficulty_level): self
    {
        $this->difficulty_level = $difficulty_level;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecipe($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->contains($ingredient)) {
            $this->ingredients->removeElement($ingredient);
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecipe() === $this) {
                $ingredient->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->setRecipe($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            // set the owning side to null (unless already changed)
            if ($tag->getRecipe() === $this) {
                $tag->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reviews[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Reviews $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setRecipe($this);
        }

        return $this;
    }

    public function removeReview(Reviews $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getRecipe() === $this) {
                $review->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|KitchenTools[]
     */
    public function getKitchenTools(): Collection
    {
        return $this->kitchen_tools;
    }

    public function addKitchenTool(KitchenTools $kitchenTool): self
    {
        if (!$this->kitchen_tools->contains($kitchenTool)) {
            $this->kitchen_tools[] = $kitchenTool;
            $kitchenTool->setRecipe($this);
        }

        return $this;
    }

    public function removeKitchenTool(KitchenTools $kitchenTool): self
    {
        if ($this->kitchen_tools->contains($kitchenTool)) {
            $this->kitchen_tools->removeElement($kitchenTool);
            // set the owning side to null (unless already changed)
            if ($kitchenTool->getRecipe() === $this) {
                $kitchenTool->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Steps[]
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Steps $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setRecipe($this);
        }

        return $this;
    }

    public function removeStep(Steps $step): self
    {
        if ($this->steps->contains($step)) {
            $this->steps->removeElement($step);
            // set the owning side to null (unless already changed)
            if ($step->getRecipe() === $this) {
                $step->setRecipe(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        
        return $this->title;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRecipe($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }

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
            $like->setRecipes($this);
        }

        return $this;
    }

    public function removeLike(RecipeLike $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getRecipes() === $this) {
                $like->setRecipes(null);
            }
        }

        return $this;
    }

    /**
     * Permet de savoir si cet article est liké par un utilisateur
     * @param User $user
     * 
     */
    public function isLikedByUser( User $user): bool
    {
        foreach ($this->likes as $like) {
            if ($like->getUser() === $user) return true;
        }
        return false;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
