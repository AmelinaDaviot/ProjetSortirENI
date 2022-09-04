<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 */
class Etat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le libelle est requis !")
     */
    private ?string $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="etat")
     */
    private Collection $etatSorties;


    public function __construct()
    {
        $this->etatSorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }

    /**
     * @return Collection
     */
    public function getEtatSorties(): Collection
    {
        return $this->etatSorties;
    }

    public function addEtatSorties(Sortie $etatSorties): self
    {
        if (!$this->etatSorties->contains($etatSorties)) {
            $this->etatSorties[] = $etatSorties;
            $etatSorties->setEtat($this);
        }

        return $this;
    }

    public function removeEtatSorties(Sortie $etatSorties): self
    {
        if ($this->etatSorties->removeElement($etatSorties)) {
            // set the owning side to null (unless already changed)
            if ($etatSorties->getEtat() === $this) {
                $etatSorties->setEtat(null);
            }
        }

        return $this;
    }


}
