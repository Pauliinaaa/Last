<?php
/**
 * Recipe entity.
 */

namespace App\Entity;

use App\Repository\RecipeRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Recipe.
 */
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\Table(name: 'recipes')]
class Recipe
{
    /**
     * Primary key.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Created at.
     *
     * @var DateTimeInterface|null
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'create')]
    #[Assert\Type(DateTimeInterface::class)]
    private ?DateTimeInterface $createdAt;

    /**
     * Updated at.
     *
     * @var DateTimeInterface|null
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'update')]
    #[Assert\Type(DateTimeInterface::class)]
    private ?DateTimeInterface $updatedAt;

    /**
     * Name.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 120)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 120)]
    private ?string $name = null;

    /**
     * Content.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 1020)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 1020)]
    private ?string $content = null;

    /**
     * Category.
     *
     * fetch="EXTRA_LAZY"
     */
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * Comment.
     */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Comment::class)]
    private Collection $comment;

    /**
     * Recipe constructor.
     */
    #[Pure]
    public function __construct()
    {
        $this->comment = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for created at.
     *
     * @return DateTimeInterface|null Created At
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at.
     *
     * @param DateTimeInterface|null $createdAt Created at
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for updated at.
     *
     * @return DateTimeInterface|null Updated at
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Setter for updated at.
     *
     * @param DateTimeInterface|null $updatedAt Updated At
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Getter for name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for name.
     *
     * @param string $name Name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for content.
     *
     * @return string|null Content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     *
     * @param string $content Content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Getter for Category.
     *
     * @return Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for Category.
     *
     * @param Category|null $category Category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * Getter for Comment.
     *
     * @return Collection
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    /**
     * @param Comment $comment
     *
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setRecipe($this);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     *
     * @return $this
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }

        return $this;
    }
}
