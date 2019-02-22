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

    public function searchByDescriptionOrCodes($query)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.description LIKE :description')
            ->orWhere('d.dsmId LIKE :dsmId')
            ->orWhere('d.icd9 LIKE :icd9')
            ->orWhere('d.icd10 LIKE :icd10')
            ->setParameter('description', '%' . $query . '%')
            ->setParameter('dsmId', '%' . $query . '%')
            ->setParameter('icd9', '%' . $query . '%')
            ->setParameter('icd10', '%' . $query . '%')
            ->orderBy('d.description', 'ASC')
            ->getQuery()
            ->getArrayResult()
            ;
    }
}
