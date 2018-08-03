<?php

namespace App\Repository;

use App\Entity\ObservationPhase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ObservationPhase|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObservationPhase|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObservationPhase[]    findAll()
 * @method ObservationPhase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObservationPhaseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ObservationPhase::class);
    }

    /**
     * @return ObservationPhase[] Returns an array of ObservationPhase objects
     */
    public function findByDataId($dataId)
    {
        return $this->createQueryBuilder('o')
            ->where('o.dataIds LIKE :dataId')
            ->setParameter('dataId', '%"' . $dataId . '"%')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?ObservationPhase
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
