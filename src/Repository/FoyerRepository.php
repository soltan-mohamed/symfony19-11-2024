<?php

namespace App\Repository;

use App\Entity\Foyer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class FoyerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Foyer::class);
    }

    public function searchByName(?string $nom): array
    {
        $qb = $this->createQueryBuilder('f');
    
        if ($nom && $nom !== '') { 
            $qb->where('f.nom LIKE :nom')
               ->setParameter('nom', '%' . $nom . '%');
        }
    
        return $qb->getQuery()->getResult();
    }

}


