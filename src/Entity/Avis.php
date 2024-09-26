<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools;


#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idAvis")]
    private ?int $idAvis = null;

    #[ORM\Column(name: "rate")]
    private ?int $rate = null;

    #[ORM\Column(name: "commentaire",type: Types::TEXT)]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: Formation::class, inversedBy: 'avis')]
    #[ORM\JoinColumn(name: "idFormation", referencedColumnName: "IdFormation")]
    private ?Formation $idFormation = null;
    

    #[ORM\ManyToOne(inversedBy: 'avis')]
    #[ORM\JoinColumn(name: "idEtudiant", referencedColumnName: "identifiant")]
    private ?Etudiant $idEtudiant = null;

    #[ORM\OneToMany(targetEntity: Rating::class, mappedBy: 'idAvis')]
    private Collection $ratings;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
    }

    public function getIdAvis(): ?int
    {
        return $this->idAvis;
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

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

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

    public function getIdFormation(): ?Formation
    {
        return $this->idFormation;
    }

    public function setIdFormation(?Formation $idFormation): static
    {
        $this->idFormation = $idFormation;

        return $this;
    }

    public function getIdEtudiant(): ?etudiant
    {
        return $this->idEtudiant;
    }

    public function setIdEtudiant(?etudiant $idEtudiant): static
    {
        $this->idEtudiant = $idEtudiant;

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setIdAvis($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getIdAvis() === $this) {
                $rating->setIdAvis(null);
            }
        }

        return $this;
    }
}
