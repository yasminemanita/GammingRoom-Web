<?php

namespace App\Repository;

use App\Entity\Jeux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Jeux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jeux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jeux[]    findAll()
 * @method Jeux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JeuxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jeux::class);
    }

    // /**
    //  * @return Jeux[] Returns an array of Jeux objects
    //  */
    
    public function search($value)
    {
        $qr=$this->createQueryBuilder('j')
        ->where("j.typePlateforme =  :plat")
        ->setParameter('val', "%$value%")
        ->andwhere('j.nom LIKE  :val or j.description LIKE  :val')
        ->setParameter('plat', "Web")
        ->getQuery();
        return 
            $qr->getResult()
        ;
    }

    
    public function getWebGames()
    {
        $qr=$this->createQueryBuilder('j')
        ->where("j.typePlateforme =  :plat")
        ->setParameter('plat', "Web")
        ->orderBy('j.id', 'DESC')
        ->getQuery();
        return 
            $qr->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Jeux
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
