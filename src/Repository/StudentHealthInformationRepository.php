<?php

namespace App\Repository;

use App\Entity\StudentHealthInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StudentHealthInformation|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentHealthInformation|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentHealthInformation[]    findAll()
 * @method StudentHealthInformation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentHealthInformationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StudentHealthInformation::class);
    }

    public function findByStudentAndCreatorUserId(Student $student)
    {
        return $this->createQueryBuilder('sh')
            ->join('sh.student', 's')
            ->where('sh.student = :student')->setParameter('student', $student)
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return StudentHealthInformation[] Returns an array of StudentHealthInformation objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StudentHealthInformation
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
