<?php
/**
 * Comment entity.
 */

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment.
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comments')]
class Comment
{
    /**
     * Primary key.
     *
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    /**
     * User nick.
     *
     * @var string
     */
    #[ORM\Column(type: 'string', length: 25)]
    #[Assert\Type(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 25)]
    private string $userNick;

    /**
     * User email.
     *
     * @var string
     */
    #[ORM\Column(type: 'string', length: 128)]
    #[Assert\Type(type: 'string')]
    #[Assert\Email]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 128)]
    private string $userEmail;

    /**
     * Content.
     *
     * @var string
     */
    #[ORM\Column(type: 'string', length: 1020)]
    #[Assert\Type(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 1020)]
    private string $content;

    #[ORM\ManyToOne(targetEntity: Recipe::class, inversedBy: 'comment')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe;

    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for User nick.
     *
     * @return string|null Result
     */
    public function getUserNick(): ?string
    {
        return $this->userNick;
    }

    /**
     * Setter for User nick.
     *
     * @param string $userNick User nick
     *
     * @return Comment
     */
    public function setUserNick(string $userNick): self
    {
        $this->userNick = $userNick;

        return $this;
    }

    /**
     * Getter for User email.
     *
     * @return string|null Result
     */
    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    /**
     * Setter for User email.
     *
     * @param string $userEmail User email
     *
     * @return Comment
     */
    public function setUserEmail(string $userEmail): self
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * Getter for Content.
     *
     * @return string|null Result
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for Content.
     *
     * @param string $content Content
     *
     * @return Comment
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Getter for Recipe.
     *
     * @return Recipe|null Result
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * Setter for Recipe.
     *
     * @param Recipe|null $recipe
     *
     * @return $this
     */
    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }
}
