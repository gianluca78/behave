<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\Measure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findItemsByMeasure(Measure $measure)
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('App\Entity\ChoiceItem', 'c', 'WITH', 'i.id = c.id')
            ->leftJoin('App\Entity\DirectObservationItem', 'd', 'WITH', 'i.id = d.id')
            ->leftJoin('App\Entity\IntegerItem', 'inte', 'WITH', 'i.id = inte.id')
            ->leftJoin('App\Entity\MeterItem', 'm', 'WITH', 'i.id = m.id')
            ->leftJoin('App\Entity\RangeItem', 'r', 'WITH', 'i.id = r.id')
            ->leftJoin('App\Entity\TextItem', 't', 'WITH', 'i.id = t.id')
            ->where('inte.measure = :measure')
            ->orWhere('t.measure = :measure')
            ->orWhere('d.measure = :measure')
            ->orWhere('r.measure = :measure')
            ->orWhere('c.measure = :measure')
            ->orWhere('m.measure = :measure')
            ->setParameter('measure', $measure)
            ->orderBy('i.positionNumber', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
