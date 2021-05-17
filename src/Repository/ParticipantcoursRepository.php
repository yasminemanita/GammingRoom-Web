<?php

namespace App\Repository;

use App\Entity\Participantcours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participantcours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participantcours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participantcours[]    findAll()
 * @method Participantcours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantcoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participantcours::class);
    }

    // /**
    //  * @return Participantcours[] Returns an array of Participantcours objects
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
    public function findOneBySomeField($value): ?Participantcours
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    //ajouter participant
    public function findOneByME($e,$m): ?array
    {
        /*$qb = $this->getEntityManager()->createQueryBuilder('p');
        $qb->select('p')
            ->from('App\Entity\Participant', 'p')
            ->where('p.evenement = ?1')
            ->andWhere('p.member = ?2')
            ->setParameter(1, $e)
            ->setParameter(2, $m);*/
        $qb = $this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Participantcours p WHERE  p.membre = :m AND p.cour = :e ');
        $qb->setParameter('e', $e);
        $qb->setParameter('m', $m);
        return $qb->getResult();

    }
    //annuler inscription
    public function delete($e,$m): ?int
    {
        /*$qb = $this->getEntityManager()->createQueryBuilder('p');
        $qb->select('p')
            ->from('App\Entity\Participant', 'p')
            ->where('p.evenement = ?1')
            ->andWhere('p.member = ?2')
            ->setParameter(1, $e)
            ->setParameter(2, $m);*/
        $qb = $this->getEntityManager()->createQuery('DELETE FROM App\Entity\Participantcours p WHERE  p.membre = :m AND p.cour = :e ');

        $qb->setParameter('e', $e);
        $qb->setParameter('m', $m);
        return $qb->getResult();

    }

    public function  listeparticipants($e):?array
    {
        $qb = $this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Participantcours p WHERE  p.cour = :e ');

        $qb->setParameter('e', $e);

        return $qb->getResult();

    }






}
