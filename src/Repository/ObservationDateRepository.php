<?php

namespace App\Repository;

use App\Entity\ObservationDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ObservationDate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObservationDate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObservationDate[]    findAll()
 * @method ObservationDate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObservationDateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ObservationDate::class);
    }

    public function findByObservationIdAndDate()
    {
        return $this->createQueryBuilder('o')
            ->where('o.observation = :observation')
            ->andWhere('')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return ObservationDate[] Returns an array of ObservationDate objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ObservationDate
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
