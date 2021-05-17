<?php

namespace App\Repository;

use App\Entity\Membre;
use App\Entity\Reactionev;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reactionev|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reactionev|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reactionev[]    findAll()
 * @method Reactionev[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReactionevRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reactionev::class);
    }

    // /**
    //  * @return Reactionev[] Returns an array of Reactionev objects
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
    public function findOneBySomeField($value): ?Reactionev
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getNBLikes($e): ?array
    {
        $qb = $this->getEntityManager()->createQuery('SELECT count(r) FROM App\Entity\Reactionev r WHERE r.evenement = :e and r.interaction = 1');
        $qb->setParameter('e', $e);
        return $qb->getResult();



    }

    public function getNBDislikes($e): ?array
    {
        $qb = $this->getEntityManager()->createQuery('SELECT count(r) FROM App\Entity\Reactionev r WHERE r.evenement = :e and r.interaction = -1');
        $qb->setParameter('e', $e);
        return $qb->getResult();



    }

    public function getCommentaires($e): ?array
    {
        $qb = $this->getEntityManager()->createQuery('SELECT r FROM App\Entity\Reactionev r WHERE r.evenement = :e and r.commentaire is not null');
        $qb->setParameter('e', $e);
        return $qb->getResult();



    }

    public function isLikedByUser($m,$e): ?array
    {
        $qb = $this->getEntityManager()->createQuery('SELECT count(r) FROM App\Entity\Reactionev r WHERE r.evenement = :e and r.membre = :m and r.interaction = 1');
        $qb->setParameter('e', $e);
        $qb->setParameter('m', $m);
        return $qb->getResult();
    }

    public function isDislikedByUser($m,$e): ?array
    {
        $qb = $this->getEntityManager()->createQuery('SELECT count(r) FROM App\Entity\Reactionev r WHERE r.evenement = :e and r.membre = :m and r.interaction = -1');
        $qb->setParameter('e', $e);
        $qb->setParameter('m', $m);
        return $qb->getResult();
    }

    public function likeEvent($m,$e): ?int
    {

        $qb = $this->getEntityManager()->createQuery('UPDATE App\Entity\Reactionev r set r.interaction=-1 WHERE r.evenement = :e and r.membre = :m and r.interaction = 1');
        $qb->setParameter('e', $e);
        $qb->setParameter('m', $m);
        return $qb->getResult();

    }
    public function dislikeEvent($m,$e): ?int
    {

        $qb = $this->getEntityManager()->createQuery('UPDATE App\Entity\Reactionev r set r.interaction=1 WHERE r.evenement = :e and r.membre = :m and r.interaction = -1');
        $qb->setParameter('e', $e);
        $qb->setParameter('m', $m);
        return $qb->getResult();

    }
}
