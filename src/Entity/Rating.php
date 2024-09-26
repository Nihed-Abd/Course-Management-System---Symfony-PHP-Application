<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idRating', type: 'integer')]
    private ?int $idRating = null;

    #[ORM\Column]
    private ?int $rate = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: Avis::class, inversedBy: 'ratings')]
    #[ORM\JoinColumn(name: 'idAvis', referencedColumnName: 'idAvis')]
    private ?Avis $idAvis = null;

    // Getters and setters...

    public function getId(): ?int
    {
        return $this->idRating;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getIdAvis(): ?Avis
    {
        return $this->idAvis;
    }

    public function setIdAvis(?Avis $idAvis): static
    {
        $this->idAvis = $idAvis;

        return $this;
    }
}
