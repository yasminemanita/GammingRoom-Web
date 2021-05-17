<?php

namespace App\Repository;

use App\Entity\RechercheProd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RechercheProd|null find($id, $lockMode = null, $lockVersion = null)
 * @method RechercheProd|null findOneBy(array $criteria, array $orderBy = null)
 * @method RechercheProd[]    findAll()
 * @method RechercheProd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RechercheProdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RechercheProd::class);
    }

    // /**
    //  * @return RechercheProd[] Returns an array of RechercheProd objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RechercheProd
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
