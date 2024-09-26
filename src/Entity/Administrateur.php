<?php

namespace App\Entity;

use App\Repository\AdministrateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools;


#[ORM\Entity(repositoryClass: AdministrateurRepository::class)]
class Administrateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $identifiant  = null;

    #[ORM\Column(name: "nomComplet", length: 255)]
    private ?string $nomComplet = null;

    #[ORM\OneToMany(targetEntity: Formation::class, mappedBy: 'idAdministrateur')]
    private Collection $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->identifiant ;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): static
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setIdAdministrateur($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getIdAdministrateur() === $this) {
                $formation->setIdAdministrateur(null);
            }
        }

        return $this;
    }
}
