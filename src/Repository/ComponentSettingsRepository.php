<?php

namespace App\Repository;

use App\Entity\ComponentSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ComponentSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComponentSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComponentSettings[]    findAll()
 * @method ComponentSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComponentSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComponentSettings::class);
    }

    // /**
    //  * @return ComponentSettings[] Returns an array of ComponentSettings objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ComponentSettings
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
