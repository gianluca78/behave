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

    public function findByStudentAndCreatorUserId(Student $student, $creatorUserId)
    {
        return $this->createQueryBuilder('o')
            ->join('o.student', 's')
            ->where('o.student = :student')->setParameter('student', $student)
            ->andWhere('s.creatorUserId = :creatorUserId')->setParameter('creatorUserId', $creatorUserId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findWithoutDatesByCreatorUserId($creatorUserId)
    {
        return $this->createQueryBuilder('o')
            ->join('o.observationScheduler', 'os')
            ->where('o.isEnabled = 1')
            ->andWhere('o.creatorUserId = :creatorUserId')->setParameter('creatorUserId', $creatorUserId)
            ->andWhere('os.hasDates = 0')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findIncomingObservations($numberOfHours)
    {
        $now = new \DateTime();
        $now->add(new \DateInterval('PT' . $numberOfHours . 'H'));

        return $this->createQueryBuilder('o')
            ->join('o.observationDates', 'd')
            ->where('d.startDateTimestamp = :startDate')->setParameter('startDate', $now->format('Y-m-d H:i') . ':00')
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
