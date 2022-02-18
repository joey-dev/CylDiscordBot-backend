<?php

namespace App\Repository;

use App\Entity\Emote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Emote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emote[]    findAll()
 * @method Emote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emote::class);
    }

    // /**
    //  * @return Emote[] Returns an array of Emote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Emote
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
