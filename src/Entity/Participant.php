<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @ORM\Table (name="Participants")
 * @UniqueEntity(fields={"email"}, message="Cette adresse email est déjà utilisée.")
 * @UniqueEntity(fields={"pseudo"}, message="Ce pseudo est déjà utilisé.")
 */
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     */
    private ?int $id;

    /**
     * @ORM\Column(name="email", type="string", length=180, unique=true)
     *
     * @Assert\NotBlank(message="L'adresse email est requise !")
     * @Assert\Email(message="L'adresse email '{{ value }}' est invalide !")
     * @Assert\Length(
     *     max = 180,
     *     maxMessage = "L'adresse email doit être composée d'au maximum {{ limit }} caractères !"
     * )
     */
    private ?string $email;

    /**
     * @ORM\Column(name="pseudo", type="string", length=30, unique=true)
     *
     * @Assert\Length(
     *     min = 4,
     *     max = 30,
     *     minMessage = "Le pseudo doit être composé d'au moins {{ limit }} caractères !",
     *     maxMessage = "Le pseudo doit être composé d'au maximum {{ limit }} caractères !"
     * )
     * @Assert\NotBlank(message="Le pseudo est requis !")
     */
    private ?string $pseudo;


    /**
     * @ORM\Column(name="roles", type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(name="password", type="string", length=100)
     */
    private string $password;

    /**
     * @Assert\NotBlank(message="Le mot de passe est requis !")
     * @Assert\Length(
     *     min = 6,
     *     max = 50,
     *     minMessage = "Le mot de passe doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le mot de passe doit contenir au maximum {{ limit }} caractères !",)
     */
    private ?string $plainPassword;
    // à rajouter lorsqu'on voudra présenter notre projet et donc sécuriser le mdp
//* @Assert\NotCompromisedPassword(message="Le mot de passe n'est pas assez complexe !", skipOnError=true)

    /**
     * @ORM\Column(name="nom", type="string", length=50)
     *
     * @Assert\NotBlank(message="Le nom est requis !")
     * @Assert\Length(
     *     min = 3,
     *     max = 50,
     *     minMessage = "Le nom doit être composé d'au moins {{ limit }} caractères !",
     *     maxMessage = "Le nom doit être composé d'au maximum {{ limit }} caractères !")
     */
    private ?string $nom;

    /**
     * @ORM\Column(name="prenom", type="string", length=50)
     *
     * @Assert\NotBlank(message="Le prénom est requis !")
     * @Assert\Length(
     *     min = 3,
     *     max = 50,
     *     minMessage = "Le prénom doit être composé d'au moins {{ limit }} caractères !",
     *     maxMessage = "Le prénom doit être composé d'au maximum {{ limit }} caractères !")
     */
    private ?string $prenom;

    /**
     * @ORM\Column(name="telephone", type="string", length=10)
     *
     * @Assert\NotBlank(message="Le numéro de téléphone est requis !")
     * @Assert\Length(
     *     min = 10,
     *     max = 10,
     *     exactMessage = "Le numéro de téléphone doit contenir {{ limit }} caractères !",
     *     )
     */
    private ?string $telephone;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="participants")
     * @ORM\JoinColumn(name="campus_id", nullable=false)
     */
    private ?Campus $campus;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, mappedBy="participants")
     */
    private $sorties;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur", orphanRemoval=true)
     */
    private $orgaSortie;



    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->organisateur = new ArrayCollection();
        $this->orgaSortie = new ArrayCollection();
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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * @param string $pseudo
     */
    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
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
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     */
    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param string $role
     * @return void
     */
    public function addRole(string $role): void {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    /**
     * @param string $role
     * @return void
     */
    public function removeRole(string $role): void {
        $this->roles = array_filter($this->roles, function (string $currentRole) use ($role) {
            return $currentRole !== $role;
        });
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
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
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->addParticipant($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            $sorty->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getOrganisateur(): Collection
    {
        return $this->organisateur;
    }

    public function addOrganisateur(Sortie $organisateur): self
    {
        if (!$this->organisateur->contains($organisateur)) {
            $this->organisateur[] = $organisateur;
            $organisateur->setOrganisateur($this);
        }

        return $this;
    }

    public function removeOrganisateur(Sortie $organisateur): self
    {
        if ($this->organisateur->removeElement($organisateur)) {
            // set the owning side to null (unless already changed)
            if ($organisateur->getOrganisateur() === $this) {
                $organisateur->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getOrgaSortie(): Collection
    {
        return $this->orgaSortie;
    }

    public function addOrgaSortie(Sortie $orgaSortie): self
    {
        if (!$this->orgaSortie->contains($orgaSortie)) {
            $this->orgaSortie[] = $orgaSortie;
            $orgaSortie->setParticipant($this);
        }

        return $this;
    }

    public function removeOrgaSortie(Sortie $orgaSortie): self
    {
        if ($this->orgaSortie->removeElement($orgaSortie)) {
            // set the owning side to null (unless already changed)
            if ($orgaSortie->getParticipant() === $this) {
                $orgaSortie->setParticipant(null);
            }
        }

        return $this;
    }


}
