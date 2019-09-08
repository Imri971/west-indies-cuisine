<?php

namespace App\Repository;

use App\Entity\KitchenTools;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method KitchenTools|null find($id, $lockMode = null, $lockVersion = null)
 * @method KitchenTools|null findOneBy(array $criteria, array $orderBy = null)
 * @method KitchenTools[]    findAll()
 * @method KitchenTools[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KitchenToolsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KitchenTools::class);
    }

    // /**
    //  * @return KitchenTools[] Returns an array of KitchenTools objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?KitchenTools
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
