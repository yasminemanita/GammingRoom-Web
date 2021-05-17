<?php

namespace App\Repository;

use App\Entity\Reactioncours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reactioncours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reactioncours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reactioncours[]    findAll()
 * @method Reactioncours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReactioncoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reactioncours::class);
    }

    // /**
    //  * @return Reactioncours[] Returns an array of Reactioncours objects
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
    public function findOneBySomeField($value): ?Reactioncours
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findlike($e, $m): ?array
    {
        /*$qb = $this->getEntityManager()->createQueryBuilder('p');
        $qb->select('p')
            ->from('App\Entity\Participant', 'p')
            ->where('p.evenement = ?1')
            ->andWhere('p.member = ?2')
            ->setParameter(1, $e)
            ->setParameter(2, $m);*/
        $qb = $this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Reactioncours p WHERE  p.membre = :m AND p.cour = :e');

        $qb->setParameter('e', $e);
        $qb->setParameter('m', $m);
        return $qb->getResult();

    }

    public function nombreObjets($idCour)
    {
        try {
            return $this->createQueryBuilder('r')
                ->select('count(r.id)')
                ->where('r.cour = :idCour')
                ->andWhere('r.interaction != 0')
                ->setParameter('idCour', $idCour)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }

    public function nombreLikes($idCour)
    {
        try {
            return $this->createQueryBuilder('r')
                ->select('count(r.id)')
                ->andWhere('r.cour = :idCour')
                ->andWhere('r.interaction = :typInteraction')
                ->setParameters(['typInteraction' => 1, 'idCour' => $idCour])
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }

    public function haveLikeDislike($idCour, $idMembre)
    {
        /*$qb = $this->getEntityManager()->createQueryBuilder('p');
        $qb->select('p')
            ->from('App\Entity\Participant', 'p')
            ->where('p.evenement = ?1')
            ->andWhere('p.member = ?2')
            ->setParameter(1, $e)
            ->setParameter(2, $m);*/
        $qb = $this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Reactioncours p WHERE  ( p.membre = :m ) AND ( p.cour = :e) AND ( p.interaction != 0 ) ');

        $qb->setParameter('e', $idCour);
        $qb->setParameter('m', $idMembre);
        return $qb->getResult();

    }

}
