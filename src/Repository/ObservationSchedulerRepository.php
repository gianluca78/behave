<?php

namespace App\Repository;

use App\Entity\ObservationScheduler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ObservationScheduler|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObservationScheduler|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObservationScheduler[]    findAll()
 * @method ObservationScheduler[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObservationSchedulerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ObservationScheduler::class);
    }

//    /**
//     * @return ObservationScheduler[] Returns an array of ObservationScheduler objects
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
    public function findOneBySomeField($value): ?ObservationScheduler
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
