<?php

namespace App\Repository;

use App\Entity\Chambre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ChambreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chambre::class);
    }

    public function searchByNumero(?string $numero): array
    {
        $qb = $this->createQueryBuilder('c');

        if ($numero) {
            $qb->where('c.numero LIKE :numero')
               ->setParameter('numero', '%' . $numero . '%');
        }

        return $qb->getQuery()->getResult();
    }

}
