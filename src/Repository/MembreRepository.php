<?php

namespace App\Repository;

use App\Entity\Membre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Membre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Membre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Membre[]    findAll()
 * @method Membre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Membre::class);
    }

    // /**
    //  * @return Membre[] Returns an array of Membre objects
    //  */

    public function findByEmailAndRole($text)
    {
        return $this->createQueryBuilder('membre')
            ->Where('membre.email LIKE :email')
            ->setParameter('email', '%'.$text.'%')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countMember(){
        return $this->createQueryBuilder('m')
            ->select( 'count(m.id) as number', 'm.role as role')
            ->groupBy('m.role')
            ->getQuery()
            ->getResult();
    }

    public function  selectLastRow() : ?int{
        try {
            return $this->createQueryBuilder('m')
                ->select('MAX(m.id)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }

    /*
    public function findOneBySomeField($value): ?Membre
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
