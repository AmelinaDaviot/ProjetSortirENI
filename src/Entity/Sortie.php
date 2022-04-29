<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank(message="Le nom de la sortie doit être renseigné.")
     */
    private ?string $nom;

    /**
     * @ORM\Column(name="date_heure_debut", type="datetime")
     * @Assert\NotBlank(message="La date de début doit être renseignée.")
     */
    private ?DateTimeInterface $dateHeureDebut;

    /**
     * @ORM\Column(name="duree", type="integer", nullable=true)
     * @Assert\Type("integer",message="La durée doit être indiquée en chiffres.")
     * @Assert\NotBlank(message="La durée doit être renseignée.")
     * @Assert\GreaterThan(value=0,message="La durée, si elle est indiquée, doit être supérieur à 0 minutes.")
     */
    private ?int $duree;

    /**
     * @ORM\Column(name="nb_inscriptions_max", type="integer")
     * @Assert\NotBlank(message="Indiquez le nombre maximum de participants.")
     */
    private ?int $nbInscriptionsMax;

    /**
     * @ORM\Column(name="infos_sortie", type="string", length=255, nullable=true)
     * @Assert\Length(max="1000",maxMessage="Trop long, maximum 1000 caractères.")
     */
    private ?string $infosSortie;

    /**
     * @ORM\Column(name="motif_annulation", type="string", length=255, nullable=true)
     */
    private ?string $motifAnnulation;

    /**
     * @ORM\Column(name="date_limite_inscription", type="datetime")
     */
    private ?DateTimeInterface $dateLimiteInscription;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, inversedBy="sorties")
     */
    private Collection $participants;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="orgaSortie")
     */
    private ?Participant $participant;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Lieu $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class)
     */
    private ?Etat $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Campus $campus;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class,inversedBy="sortiesOrganisees")
     */
    private ?Participant $organisateur;



    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }


    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateHeureDebut(): ?DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    /**
     * @param DateTimeInterface $dateHeureDebut
     */
    public function setDateHeureDebut(DateTimeInterface $dateHeureDebut): void
    {
        $this->dateHeureDebut = $dateHeureDebut;
    }

    /**
     * @return int|null
     */
    public function getDuree(): ?int
    {
        return $this->duree;
    }

    /**
     * @param int|null $duree
     */
    public function setDuree(?int $duree): void
    {
        $this->duree = $duree;
    }

    /**
     * @return int|null
     */
    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    /**
     * @param int $nbInscriptionsMax
     */
    public function setNbInscriptionsMax(int $nbInscriptionsMax): void
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;
    }

    /**
     * @return string|null
     */
    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    /**
     * @param string|null $infosSortie
     */
    public function setInfosSortie(?string $infosSortie): void
    {
        $this->infosSortie = $infosSortie;
    }

    /**
     * @return string|null
     */
    public function getMotifAnnulation(): ?string
    {
        return $this->motifAnnulation;
    }

    /**
     * @param string|null $motifAnnulation
     */
    public function setMotifAnnulation(?string $motifAnnulation): void
    {
        $this->motifAnnulation = $motifAnnulation;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateLimiteInscription(): ?DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    /**
     * @param DateTimeInterface $dateLimiteInscription
     */
    public function setDateLimiteInscription(DateTimeInterface $dateLimiteInscription): void
    {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    /**
     * @param Participant $participant
     */
    public function addParticipant(Participant $participant): void
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }
    }

    /**
     * @param Participant $participant
     */
    public function removeParticipant(Participant $participant): void
    {
        $this->participants->removeElement($participant);
    }

    /**
     * @return Participant|null
     */
    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    /**
     * @param Participant|null $organisateur
     */
    public function setOrganisateur(?Participant $organisateur): void
    {
        $this->organisateur = $organisateur;
    }

    /**
     * @return Participant|null
     */
    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    /**
     * @param Participant|null $participant
     */
    public function setParticipant(?Participant $participant): void
    {
        $this->participant = $participant;
    }

    /**
     * @return Lieu|null
     */
    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    /**
     * @param Lieu|null $lieu
     */
    public function setLieu(?Lieu $lieu): void
    {
        $this->lieu = $lieu;
    }

    /**
     * @return Etat|null
     */
    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    /**
     * @param Etat|null $etat
     */
    public function setEtat(?Etat $etat): void
    {
        $this->etat = $etat;
    }

    /**
     * @return Campus|null
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus|null $campus
     */
    public function setCampus(?Campus $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return Participant|null
     */
    public function getSortiesOrganisees(): ?Participant
    {
        return $this->sortiesOrganisees;
    }

    /**
     * @param Participant|null $sortiesOrganisees
     */
    public function setSortiesOrganisees(?Participant $sortiesOrganisees): void
    {
        $this->sortiesOrganisees = $sortiesOrganisees;
    }
}
