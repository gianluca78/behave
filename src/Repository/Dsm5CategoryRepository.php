<?php

namespace App\Repository;

use App\Entity\Core\Dsm5Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Dsm5Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dsm5Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dsm5Category[]    findAll()
 * @method Dsm5Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Dsm5CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Dsm5Category::class);
    }

//    /**
//     * @return Dsm5Category[] Returns an array of Dsm5Category objects
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
    public function findOneBySomeField($value): ?Dsm5Category
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
