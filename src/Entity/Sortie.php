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
    private ?int $id;

    /**
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank(message="Le nom de la sortie doit être renseigné.")
     */
    private ?string $nom;

    /**
     * @ORM\Column(name="date_heure_debut", type="datetime")
     * @Assert\GreaterThan("today", message="La date de la sortie doit être égale à la date du jour ou plus tard.")
     * @Assert\NotBlank(message="La date de début doit être renseignée.")
     */
    private ?DateTimeInterface $dateHeureDebut;

    /**
     * @ORM\Column(name="duree", type="integer", nullable=true)
     * @Assert\Type("integer",message="La durée doit être indiquée en chiffres.")
     * @Assert\NotBlank(message="La durée doit être renseignée.")
     * @Assert\GreaterThan(value=0, message="La durée doit être supérieur à 0 minute.")
     */
    private ?int $duree;

    /**
     * @ORM\Column(name="nb_inscriptions_max", type="integer")
     * @Assert\NotBlank(message="Indiquez le nombre maximum de participants.")
     * @Assert\Positive(message="Le nombre d'inscriptions maximum doit être supérieur à 0.")
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
     * @Assert\GreaterThan("today +2 hours", message="La date limite des inscriptions doit être au minimum prévue 2 heures après la date de début.")
     * @Assert\NotBlank(message="La date limite d'inscription doit être renseignée.")
     */
    private ?DateTimeInterface $dateLimiteInscription;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, mappedBy="sorties")
     */
    private Collection $participants;


    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Campus $campus;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="sortiesOrganisees")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Participant $organisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="etatSorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Etat $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Lieu $lieu=null;



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

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): void
    {
        $this->lieu = $lieu;

    }


}
