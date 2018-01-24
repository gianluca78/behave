<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\Observation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findItemsByObservation(Observation $observation)
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('App\Entity\TextItem', 't', 'WITH', 'i.id = t.id')
            ->leftJoin('App\Entity\FrequencyItem', 'f', 'WITH', 'i.id = f.id')
            ->where('t.observation = :observation')
            ->orWhere('f.observation = :observation')
            ->setParameter('observation', $observation)
            ->orderBy('i.positionNumber', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
