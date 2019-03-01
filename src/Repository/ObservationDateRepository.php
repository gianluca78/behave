<?php

namespace App\Repository;

use App\Entity\ObservationDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Observation;

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

    public function deleteFromTomorrow(Observation $observation)
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('o')
            ->delete()
            ->where('o.observation = :observation')
            ->andWhere('o.startDateTimestamp >= :startDate')
            ->setParameter('observation', $observation)
            ->setParameter('startDate', $now->format('Y-m-d H:i:s'))
            ->getQuery()
            ->execute()
            ;
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

    public function findIncomingObservations($numberOfHours)
    {
        $now = new \DateTime();
        $now->add(new \DateInterval('PT' . $numberOfHours . 'H'));

        return $this->createQueryBuilder('od')
            ->where('od.startDateTimestamp = :startDate')->setParameter('startDate', $now->format('Y-m-d H:i') . ':00')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findFutureObservations($observerUserId)
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('od')
            ->join('od.observation', 'o')
            ->where('od.startDateTimestamp >= :startDate')->setParameter('startDate', $now->format('U'))
            ->andWhere('o.observerUserId = :observerUserId')->setParameter('observerUserId', $observerUserId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findNextObservationDate($observation)
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('od')
            ->where('od.startDateTimestamp >= :now')->setParameter('now', $now->format('Y-m-d H:i') . ':00')
            ->andWhere('od.observation = :observation')->setParameter('observation', $observation)
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}
