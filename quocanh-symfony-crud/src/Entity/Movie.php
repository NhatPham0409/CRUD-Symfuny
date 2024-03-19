<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $plot = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotBlank]
    #[Assert\Range(min: 1888)]
    private ?int $release_year = null;

    #[ORM\Column]
    #[Assert\Range(notInRangeMessage: 'The rating must be between 0 and 10', min: 0, max: 10)]
    private ?float $rating_star = null;

    #[ORM\Column(length: 255)]
    private ?string $movieId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMovieId(): ?string
    {
        return $this->movieId;
    }

    public function setMovieId(string $movieId): static
    {
        $this->movieId = $movieId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPlot(): ?string
    {
        return $this->plot;
    }

    public function setPlot(?string $plot): static
    {
        $this->plot = $plot;

        return $this;
    }

    public function getReleaseYear(): ?int
    {
        return $this->release_year;
    }

    public function setReleaseYear(int $release_year): static
    {
        $this->release_year = $release_year;

        return $this;
    }

    public function getRatingStar(): ?float
    {
        return $this->rating_star;
    }

    public function setRatingStar(float $rating_star): static
    {
        $this->rating_star = $rating_star;

        return $this;
    }

    public function toArrayMovie(): array
    {
        return [
            'id' => $this->getId(),
            'movie_id' => $this->getMovieId(),
            'title' => $this->getTitle(),
            'plot' => $this->getPlot(),
            'release_year' => $this->getReleaseYear(),
            'rating_star' => $this->getRatingStar(),
        ];
    }
}
