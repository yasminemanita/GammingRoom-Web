<?php

namespace App\Repository;

use App\Entity\Evenement;
use App\Entity\Membre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    // /**
    //  * @return Evenement[] Returns an array of Evenement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Evenement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getEventPart($idp,$start,$end): ?array
    {
        $qb = $this->getEntityManager()->createQuery('SELECT e FROM App\Entity\Evenement e, App\Entity\Participant p WHERE  p.member = :m AND p.evenement = e ');
        $qb->setParameter('m', $idp);
        return $qb->getResult();



    }

    public function getNBParticipants($e): ?array
    {
        $qb = $this->getEntityManager()->createQuery('SELECT count(p) FROM App\Entity\Participant p WHERE p.evenement = :e ');
        $qb->setParameter('e', $e);
        return $qb->getResult();



    }

    public function findOffreByNsc($nomevent,$c){
        return $this->createQueryBuilder('evenement')
            ->where('evenement.nomevent LIKE :nomevent OR evenement.categorie = :c OR evenement.lieu LIKE :nomevent')
            ->setParameter('nomevent', '%'.$nomevent.'%')
            ->setParameter('c', '%'.$c.'%')
            ->getQuery()
            ->getResult();
    }

    public function upComingEvents(){
        $d=new \DateTime('now');
       /* return $this->createQueryBuilder('evenement')
            ->where('evenement.datedeb >= :now')
            ->setParameter('now', '%'.$d->format('Y-m-d 00:00:00.0 UTC (+00:00)').'%')
            ->getQuery()
            ->getResult();*/
        $qb = $this->getEntityManager()->createQuery('SELECT e FROM App\Entity\Evenement e WHERE  e.datedeb >= :now ORDER BY e.datedeb DESC');
        $qb->setParameter('now', $d);
        return $qb->getResult();
    }

    public function eventCat($c){
        $qb = $this->getEntityManager()->createQuery('SELECT e FROM App\Entity\Evenement e WHERE  e.categorie = :c');
        $qb->setParameter('c', $c);
        return $qb->getResult();
    }


}
