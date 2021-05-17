<?php

namespace App\Repository;

use App\Entity\Cour;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cour|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cour|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cour[]    findAll()
 * @method Cour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cour::class);
    }

    // /**
    //  * @return Cour[] Returns an array of Cour objects
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
    public function findOneBySomeField($value): ?Cour
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /*
    public function findStudentByTitre($nomcours)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.nomcours LIKE :val')
            ->setParameter('val', $nomcours)
            ->getQuery()
            ->getResult()
            ;
    }
    */


    //ajax rechercher
    public function findOffreByNsc($nomcours){
        return $this->createQueryBuilder('cour')
            ->where('cour.nomcours LIKE :nomcours')
            ->setParameter('nomcours', '%'.$nomcours.'%')
            ->getQuery()
            ->getResult();
    }

    //nothing
    public  function  findAllVisibleQuery(): Query
    {
        return $this->findAllVisibleQuery();

        $query->getQuery();
    }

    public function getEventPart($idp): ?array
    {
        $qb = $this->getEntityManager()->createQuery('SELECT e FROM App\Entity\Cour e, App\Entity\Participantcours p WHERE  p.membre = :m AND p.cour = e ');
        $qb->setParameter('m', $idp);
        return $qb->getResult();



    }


}
