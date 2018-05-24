<?php

namespace App\Repository;

use App\Entity\Observation;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ObservationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Observation::class);
    }

    public function findByStudentAndCreatorUsername(Student $student, $creatorUsername)
    {
        return $this->createQueryBuilder('o')
            ->join('o.student', 's')
            ->where('o.student = :student')->setParameter('student', $student)
            ->andWhere('s.creatorUsername = :creatorUsername')->setParameter('creatorUsername', $creatorUsername)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('o')
            ->where('o.something = :value')->setParameter('value', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
