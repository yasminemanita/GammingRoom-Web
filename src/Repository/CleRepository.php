<?php

namespace App\Repository;

use App\Entity\Cle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cle[]    findAll()
 * @method Cle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cle::class);
    }


    // /**
    //  * @return Cle[] Returns an array of Cle objects
    //  */

    public function findByID($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.$idcle = :val')
            ->setParameter('val', $value)

            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Cle
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
