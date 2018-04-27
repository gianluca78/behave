<?php

namespace App\Repository;

use App\Entity\DirectObservationItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DirectObservationItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method DirectObservationItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method DirectObservationItem[]    findAll()
 * @method DirectObservationItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectObservationItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DirectObservationItem::class);
    }

//    /**
//     * @return DirectObservationItem[] Returns an array of DirectObservationItem objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DirectObservationItem
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
