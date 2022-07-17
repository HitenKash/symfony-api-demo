<?php

namespace App\Entity;

trait MovieTrait
{
    /**
     * @var movie
     *
     * @ORM\ManyToOne(targetEntity="Movies")
     * @ORM\JoinColumn(name="movie_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $movie;
    
    public function getMovie(): ?Movies
    {
        return $this->movie;
    }

    public function setMovie(Movies $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
