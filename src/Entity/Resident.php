<?php

namespace App\Entity;

use App\Repository\ResidentRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Foyer;

#[ORM\Entity(repositoryClass: ResidentRepository::class)]
class Resident extends Etudiant
{
    #[ORM\ManyToOne(targetEntity: Foyer::class, inversedBy: 'residents')]
    #[ORM\JoinColumn(nullable: false)]
    private $foyer;

    #[ORM\Column(type: 'date')]
    private $dateEntree;

    #[ORM\Column(type: 'date', nullable: true)]
    private $dateSortie;


    public function getFoyer(): ?Foyer
    {
        return $this->foyer;
    }

    public function setFoyer(?Foyer $foyer): self
    {
        $this->foyer = $foyer;
        return $this;
    }

    public function getDateEntree(): ?\DateTimeInterface
    {
        return $this->dateEntree;
    }

    public function setDateEntree(\DateTimeInterface $dateEntree): self
    {
        $this->dateEntree = $dateEntree;
        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(?\DateTimeInterface $dateSortie): self
    {
        $this->dateSortie = $dateSortie;
        return $this;
    }
}
