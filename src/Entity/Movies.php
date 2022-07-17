<?php

namespace App\Entity;

use App\Repository\MoviesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass=MoviesRepository::class)
 */
class Movies
{
    /**
     * @Groups({"movie_default"})
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
     * @Groups({"movie_create","movie_default"})
     * @Assert\NotBlank(message="date required")
     * @Assert\Regex("/^([0]?[1-9]|[1|2][0-9]|[3][0|1])[-]([0]?[1-9]|[1][0-2])[-]([0-9]{4}|[0-9]{2})$/")
     * @ORM\Column(type="string", length=10)
     */
    private $releaseDate;

    /**
     * @Groups({"movie_create","movie_default"})
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your director name must be at least 2 characters",
     *      maxMessage = "Your director name cannot be longer than 50 characters"
     * )
     * @Assert\NotBlank(message="director name required")
     * @ORM\Column(type="string", length=255)
     */
    private $director;

    /**
     * @Groups({"movie_create","movie_default"})
     * @Assert\Count(
     *      min = "1",
     *      minMessage = "You must specify at least one cast"
     * )
     * @Assert\Valid
     * @ORM\OneToMany(targetEntity="MovieCasts", mappedBy="movie", cascade={"persist"})
     */
    private $casts;

    /**
     * @Groups({"movie_create","movie_default"})
     * @Assert\Count(
     *      min = "1",
     *      minMessage = "You must specify at least one rating"
     * )
     * @Assert\Valid
     * @ORM\OneToMany(targetEntity="MovieRatings", mappedBy="movie", cascade={"persist"})
     */
    private $ratings;

    /**
     * @var createdBy
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", onDelete="CASCADE")
     */
    private $createdBy;

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

    public function getReleaseDate(): ?string
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(string $releaseDate): self
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): self
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->casts = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function addCasts(MovieCasts $cast): self
    {
        $this->casts[] = $cast;
        return $this;
    }

    public function removeCasts(MovieCasts $cast): boolean
    {
        return $this->casts->removeElement($cast);
    }


    public function setCasts(): self
    {
        $casts = $this->casts;
        if(
            !(
                $this->casts instanceof MovieCasts && 
                $this->casts instanceof ArrayCollection
            ) && count($this->casts)
        ) {
            $this->casts = [];
            foreach($casts as $cast) {
                $newCast = new MovieCasts();
                $newCast->setName($cast);
                $newCast->setMovie($this);
                $this->addCasts($newCast);
            }
        }
        unset($casts);
        return $this;
    }

    public function getCasts(): array
    {
        return $this->casts;
    }

    public function addRatings(MovieRatings $rating): self
    {
        $this->ratings[] = $rating;
        return $this;
    }

    public function removeRatings(MovieRatings $rating): boolean
    {
        return $this->ratings->removeElement($rating);
    }

    public function setRatings(): array
    {
        $ratings = $this->ratings;
        if(
            !(
                $this->ratings instanceof MovieRatings 
                && $this->ratings instanceof ArrayCollection
            ) && count($this->ratings)
        ) {
            $this->ratings = [];
            foreach($ratings as $name => $rating) {
                $newRatings = new MovieRatings();
                $newRatings->setName($name);
                $newRatings->setRatings($rating);
                $newRatings->setMovie($this);
                $this->addRatings($newRatings);
            }
        }
        unset($ratings);
        return $this->ratings;
    }

    public function getRatings(): array
    {
        return $this->ratings;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getCast(): ?array
    {
        $returnData = [];
        if($this->casts){
            foreach($this->casts as $cast) {
                array_push($returnData, $cast->getName());
            }
        }
        return $returnData;
    }

    public function getRating(): ?array
    {
        $returnData = [];

        if($this->ratings){
            foreach($this->ratings as $rating) {
                $returnData[$rating->getName()] = $rating->getRatings();
            }
        }

        return $returnData;
    }
}
