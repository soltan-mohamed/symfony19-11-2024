<?php
namespace App\Entity;

use App\Repository\FoyerRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FoyerRepository::class)]
class Foyer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 70)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Assert\Regex(
        pattern: "/^[A-Za-z]/", // Vérifie que le nom commence par une lettre
        message: "Le nom doit commencer par une lettre, pas un chiffre."
    )]
    #[Assert\Regex(
        pattern: "/^[^\W\d]/", // Vérifie que le nom ne commence pas par un symbole ou un chiffre
        message: "Le nom ne doit pas commencer par un symbole."
    )]
    private $nom;

    #[ORM\Column(type: "string", length: 250)]
    #[Assert\NotBlank(message: "L'adresse ne peut pas être vide.")]
    private $adresse;

    #[ORM\OneToMany(mappedBy: "foyer", targetEntity: Chambre::class)]
    private $chambres;

    #[ORM\OneToMany(mappedBy: "foyer", targetEntity: Resident::class)]
    private $residents;

    #[ORM\Column(type: "string", length: 10)]
    #[Assert\NotBlank(message: "Le genre ne peut pas être vide.")]
    private $genre;

    #[ORM\Column(type: "string", length: 50)]
    #[Assert\NotBlank(message: "Le gouvernorat ne peut pas être vide.")]
    private $gouvernorat;

    #[ORM\Column(type: "integer")]
    #[Assert\NotNull(message: "La capacité ne peut pas être nulle.")]
    #[Assert\GreaterThan(value: 0, message: "La capacité doit être supérieure à 0.")]
    private $capacite;

    public function __construct()
    {
        $this->chambres = new ArrayCollection();
        $this->residents = new ArrayCollection();
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChambres(): Collection
    {
        return $this->chambres;
    }

    public function getResidents(): Collection
    {
        return $this->residents;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;
        return $this;
    }

    public function getGouvernorat(): ?string
    {
        return $this->gouvernorat;
    }

    public function setGouvernorat(string $gouvernorat): self
    {
        $this->gouvernorat = $gouvernorat;
        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;
        return $this;
    }

    public function __toString(): string
    {
        return $this->getNom();
    }
}
