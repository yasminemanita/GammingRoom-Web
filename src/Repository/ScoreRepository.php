<?php

namespace App\Repository;

use App\Entity\Score;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Score|null find($id, $lockMode = null, $lockVersion = null)
 * @method Score|null findOneBy(array $criteria, array $orderBy = null)
 * @method Score[]    findAll()
 * @method Score[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Score::class);
    }

    // /**
    //  * @return Score[] Returns an array of Score objects
    //  */
    
    public function findByJeuxAndMembre($jid,$mbmid)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.jeux = :j')
            ->andWhere('s.membre = :m')
            ->setParameter('j', $jid)
            ->setParameter('m', $mbmid)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findByJeuxOrderd($jid)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.jeux = :j')
            ->setParameter('j', $jid)
            ->orderBy('s.score', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Score
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
