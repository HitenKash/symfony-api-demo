<?php

namespace App\Entity;

use App\Repository\RatingAgencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="`movie_ratings`")
 * @ORM\Entity(repositoryClass=RatingAgencyRepository::class)
 */
class MovieRatings
{

    use MovieTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"movie_create","movie_default"})
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your name must be at least 2 characters",
     *      maxMessage = "Your name cannot be longer than 50 characters"
     * )
     * @Assert\NotBlank(message="name required")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @Groups({"movie_create","movie_default"})
     * @Assert\PositiveOrZero(message="invalid rating")
     * @Assert\NotBlank(message="ratings required")
     * @ORM\Column(type="float")
     */
    private $ratings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->isActive = true;
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

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getRatings(): ?float
    {
        return $this->ratings;
    }

    public function setRatings(float $ratings): self
    {
        $this->ratings = $ratings;

        return $this;
    }
}
