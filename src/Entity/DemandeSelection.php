<?php

namespace App\Entity;

use App\Repository\DemandeSelectionRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Chambre;
use App\Entity\Foyer; 

#[ORM\Entity(repositoryClass: DemandeSelectionRepository::class)]
class DemandeSelection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Chambre::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chambre $chambre = null;

    #[ORM\Column(type: "datetime")]
    private \DateTime $dateDemande;

    #[ORM\Column(length: 255)]
    private string $statut;

    #[ORM\ManyToOne(targetEntity: Foyer::class, inversedBy: 'demandesSelection')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Foyer $foyer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(Chambre $chambre): self
    {
        $this->chambre = $chambre;

        return $this;
    }

    public function getDateDemande(): \DateTime
    {
        return $this->dateDemande;
    }

    public function setDateDemande(\DateTime $dateDemande): self
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getFoyer(): ?Foyer
    {
        return $this->foyer;
    }

    public function setFoyer(Foyer $foyer): self
    {
        $this->foyer = $foyer;

        return $this;
    }
}
