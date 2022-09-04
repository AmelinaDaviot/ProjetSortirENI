<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 */
class Lieu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $rue;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="lieu")
     */
    private Collection $sorties;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="lieux")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Ville $ville;


    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;

    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): void
    {
        $this->rue = $rue;

    }


    public function __toString(): ?string
    {
        return $this->getNom();
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorties(Sortie $sorties): self
    {
        if (!$this->sorties->contains($sorties)) {
            $this->sorties[] = $sorties;
            $sorties->setLieu($this);
        }

        return $this;
    }

    public function removeSorties(Sortie $sorties): self
    {
        if ($this->sorties->removeElement($sorties)) {
            // set the owning side to null (unless already changed)
            if ($sorties->getLieu() === $this) {
                $sorties->setLieu(null);
            }
        }

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }



}
