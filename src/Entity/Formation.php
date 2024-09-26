<?php
namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'IdFormation', type: 'integer')]
    private ?int $IdFormation = null;
    
    #[ORM\Column(name: "nomFormation", length: 50)]
    private ?string $nomFormation = null;

    #[ORM\Column(name: "description", length: 255)]
    private ?string $description = null;
    
    #[ORM\Column(name: "dateDebut", type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;
    
    #[ORM\Column(name: "dateFin", type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;
    
    #[ORM\Column(name: "prix")]
    private ?float $prix = null;

    #[ORM\Column(name: "idEtudiant", type: "integer", nullable: true)]
    private ?int $idEtudiant = null;

    #[ORM\OneToMany(targetEntity: Etudiant::class, mappedBy: 'formation')]
    private Collection $etudiants;

    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'idFormation')]
    private Collection $avis;

    #[ORM\ManyToOne(targetEntity: Administrateur::class)]
    #[ORM\JoinColumn(name: "idAdministrateur", referencedColumnName: "identifiant")]
    private ?Administrateur $idAdministrateur = null;

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
        $this->avis = new ArrayCollection();
    }

    public function getIdFormation(): ?int
    {
        return $this->IdFormation;
    }

    public function getNomFormation(): ?string
    {
        return $this->nomFormation;
    }

    public function setNomFormation(?string $nomFormation): static
    {
        $this->nomFormation = $nomFormation;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function getIdAdministrateur(): ?Administrateur
    {
        return $this->idAdministrateur;
    }

    public function setIdAdministrateur(?Administrateur $idAdministrateur): static
    {
        $this->idAdministrateur = $idAdministrateur;
        return $this;
    }

    public function getIdEtudiant(): ?int
    {
        return $this->idEtudiant;
    }

    public function setIdEtudiant(?int $idEtudiant): static
    {
        $this->idEtudiant = $idEtudiant;
        return $this;
    }

    /**
     * @return Collection<int, Etudiant>
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): static
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants[] = $etudiant;
            $etudiant->setFormation($this);
        }
        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): static
    {
        $this->etudiants->removeElement($etudiant);
        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvis(Avis $avis): static
    {
        if (!$this->avis->contains($avis)) {
            $this->avis[] = $avis;
            $avis->setIdFormation($this);
        }
        return $this;
    }

    public function removeAvis(Avis $avis): static
    {
        $this->avis->removeElement($avis);
        return $this;
    }
}
