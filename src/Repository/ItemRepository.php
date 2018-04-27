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
            ->leftJoin('App\Entity\ChoiceItem', 'c', 'WITH', 'i.id = c.id')
            ->leftJoin('App\Entity\BehavioralRecordingItem', 'b', 'WITH', 'i.id = b.id')
            ->leftJoin('App\Entity\DirectObservationItem', 'd', 'WITH', 'i.id = d.id')
            ->leftJoin('App\Entity\IntegerItem', 'inte', 'WITH', 'i.id = inte.id')
            ->leftJoin('App\Entity\MeterItem', 'm', 'WITH', 'i.id = m.id')
            ->leftJoin('App\Entity\RangeItem', 'r', 'WITH', 'i.id = r.id')
            ->leftJoin('App\Entity\TextItem', 't', 'WITH', 'i.id = t.id')
            ->where('inte.observation = :observation')
            ->orWhere('t.observation = :observation')
            ->orWhere('b.observation = :observation')
            ->orWhere('d.observation = :observation')
            ->orWhere('r.observation = :observation')
            ->orWhere('c.observation = :observation')
            ->orWhere('m.observation = :observation')
            ->setParameter('observation', $observation)
            ->orderBy('i.positionNumber', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
