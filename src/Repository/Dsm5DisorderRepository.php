<?php

namespace App\Repository;

use App\Entity\Core\Dsm5Disorder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Dsm5Disorder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dsm5Disorder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dsm5Disorder[]    findAll()
 * @method Dsm5Disorder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Dsm5DisorderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Dsm5Disorder::class);
    }

//    /**
//     * @return Dsm5Disorder[] Returns an array of Dsm5Disorder objects
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
    public function findOneBySomeField($value): ?Dsm5Disorder
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
