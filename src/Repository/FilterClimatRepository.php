<?php

namespace App\Repository;

use App\Entity\FilterClimat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FilterClimat|null find($id, $lockMode = null, $lockVersion = null)
 * @method FilterClimat|null findOneBy(array $criteria, array $orderBy = null)
 * @method FilterClimat[]    findAll()
 * @method FilterClimat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilterClimatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FilterClimat::class);
    }

    // /**
    //  * @return FilterClimat[] Returns an array of FilterClimat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FilterClimat
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
