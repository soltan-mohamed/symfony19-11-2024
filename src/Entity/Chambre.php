<?php
namespace App\Entity;

use App\Repository\ChambreRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Foyer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChambreRepository::class)]
class Chambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 55)]
    #[Assert\NotBlank(message: "Le numéro de chambre ne peut pas être vide.")]
    #[Assert\Regex(
        pattern: "/^[^a-zA-Z\W]/",
        message: "Le numéro de chambre ne doit pas commencer par une lettre ou un symbole."
    )]
    private $numero;

    #[ORM\Column(type: "integer")]
    #[Assert\NotNull(message: "La capacité ne peut pas être nulle.")]
    #[Assert\GreaterThan(value: 0, message: "La capacité doit être supérieure à 0.")]
    private $capacite;

    #[ORM\ManyToOne(targetEntity: Foyer::class, inversedBy: 'chambres')]
    #[ORM\JoinColumn(nullable: false)]
    private $foyer;

    #[ORM\Column(type: 'string', length: 10)]
    private $etat;

    #[ORM\Column(type: 'string', length: 20)]
    private $typeLit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

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

    public function getFoyer(): ?Foyer
    {
        return $this->foyer;
    }

    public function setFoyer(?Foyer $foyer): self
    {
        $this->foyer = $foyer;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getTypeLit(): ?string
    {
        return $this->typeLit;
    }

    public function setTypeLit(string $typeLit): self
    {
        $this->typeLit = $typeLit;

        return $this;
    }
}
