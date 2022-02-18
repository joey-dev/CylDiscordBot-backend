<?php

namespace App\Repository;

use App\Entity\PluginSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PluginSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method PluginSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method PluginSettings[]    findAll()
 * @method PluginSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PluginSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PluginSettings::class);
    }

    // /**
    //  * @return PluginSettings[] Returns an array of PluginSettings objects
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
    public function findOneBySomeField($value): ?PluginSettings
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
