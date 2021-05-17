<?php

namespace App\Repository;

use App\Entity\CategoriMembre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoriMembre|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriMembre|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriMembre[]    findAll()
 * @method CategoriMembre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriMembreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriMembre::class);
    }

    // /**
    //  * @return CategoriMembre[] Returns an array of CategoriMembre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoriMembre
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
