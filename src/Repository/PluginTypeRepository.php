<?php

namespace App\Repository;

use App\Entity\PluginType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PluginType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PluginType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PluginType[]    findAll()
 * @method PluginType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PluginTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PluginType::class);
    }

    // /**
    //  * @return PluginType[] Returns an array of PluginType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PluginType
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
