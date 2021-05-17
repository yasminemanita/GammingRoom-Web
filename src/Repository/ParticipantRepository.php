<?php

namespace App\Repository;

use App\Entity\Participant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    // /**
    //  * @return Participant[] Returns an array of Participant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Participant
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneByME($e,$m): ?array
    {
        /*$qb = $this->getEntityManager()->createQueryBuilder('p');
        $qb->select('p')
            ->from('App\Entity\Participant', 'p')
            ->where('p.evenement = ?1')
            ->andWhere('p.member = ?2')
            ->setParameter(1, $e)
            ->setParameter(2, $m);*/
        $qb = $this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Participant p WHERE  p.member = :m AND p.evenement = :e ');
        $qb->setParameter('e', $e);
        $qb->setParameter('m', $m);
        return $qb->getResult();

    }

    public function delete($e,$m): ?int
    {

        $qb = $this->getEntityManager()->createQuery('DELETE FROM App\Entity\Participant p WHERE  p.member = :m AND p.evenement = :e ');
        $qb->setParameter('e', $e);
        $qb->setParameter('m', $m);
        return $qb->getResult();

    }

    public function updateRound($idp): ?int
    {

        $qb = $this->getEntityManager()->createQuery('UPDATE App\Entity\Participant p set p.round=p.round+1 WHERE  p.id = :id');
        $qb->setParameter('id', $idp);
        return $qb->getResult();

    }

    public function findIdMember($e): ?array
    {

        $qb = $this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Participant p WHERE  p.evenement = :e ');
        $qb->setParameter('e', $e);
        return $qb->getResult();

    }

    public function repartitionDual($m,$c,$e): ?int
    {
        $qb = $this->getEntityManager()->createQuery('UPDATE App\Entity\Participant p set p.duel=:c WHERE  p.member = :m and p.evenement = :e ');
        $qb->setParameter('c', $c);
        $qb->setParameter('m', $m);
        $qb->setParameter('e', $e);
        return $qb->getResult();

    }

    public function eventParts($e): ?array
    {

        $qb = $this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Participant p WHERE  p.evenement = :e ');
        $qb->setParameter('e', $e);
        return $qb->getResult();

    }

    public function distEventParts($e): ?array
    {

        $qb = $this->getEntityManager()->createQuery('SELECT DISTINCT p FROM App\Entity\Participant p WHERE  p.evenement = :e ');
        $qb->setParameter('e', $e);
        return $qb->getResult();

    }





}
