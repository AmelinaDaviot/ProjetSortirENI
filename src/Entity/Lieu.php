<?php

namespace App\Entity;

use App\Repository\LieuRepository;
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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rue;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="lieu")
     */
    private $sorties;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville", inversedBy="lieus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

//    /**
//     * @return Collection|Sortie[]
//     */
//    public function getSorties(): Collection
//    {
//        return $this->sorties;
//    }
//
//    public function addSorty(Sortie $sorty): self
//    {
//        if (!$this->sorties->contains($sorty)) {
//            $this->sorties[] = $sorty;
//            $sorty->setLieu($this);
//        }
//
//        return $this;
//    }
//
//    public function removeSorty(Sortie $sorty): self
//    {
//        if ($this->sorties->contains($sorty)) {
//            $this->sorties->removeElement($sorty);
//            // set the owning side to null (unless already changed)
//            if ($sorty->getLieu() === $this) {
//                $sorty->setLieu(null);
//            }
//        }
//
//        return $this;
//    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function __toString(): ?string
    {
        return $this->getNomLieu();
    }

}
