<?php

namespace App\Repository;

use App\Entity\Announces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Announces|null find($id, $lockMode = null, $lockVersion = null)
 * @method Announces|null findOneBy(array $criteria, array $orderBy = null)
 * @method Announces[]    findAll()
 * @method Announces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnouncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Announces::class);
    }

    /*public function findAnnouncesByCity(string $query)
    {
        $qb = $this->createQueryBuilder(alias:'p');
        $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like(x:'p.city', y:'query'),
                    ),
                )
            )
            ->setParameter(key:'query', value:'%' . $query . '%')
            ;
            return $qb
                    ->getQuery()
                    ->getResult();
    }*/


    // /**
    //  * @return Announces[] Returns an array of Announces objects
    //  */

    public function findAnnouncesByCity(string $query)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.City = :val')
            ->setParameter('val', $query)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAnnouncesByUser(string $query)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user_ID = :val')
            ->setParameter('val', $query)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByID($value): ?Announces
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // public function findOneBySomeField($value): ?Announces
    // {
    //     return $this->createQueryBuilder('a')
    //         ->andWhere('a.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }
}
