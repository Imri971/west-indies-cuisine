<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    // /**
    //  * @return Recipe[] Returns an array of Recipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    // public function searchRecipe($critere): ?Recipe
    // {
    //     return $this->createQueryBuilder('r')
    //         ->leftJoin('r.tags','tag')
    //         ->where('tag.name= :tagName')
    //         ->setParameter('tagName', $critere['tag']->getName())
    //         ->andWhere('r.ingredients.name = :ingredientName')
    //         ->setParameter('ingredientName', $critere['ingredient']->getName())
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }
    
    public function search($word)
    {
        return $this->createQueryBuilder('a')
            ->andWhere("a.title LIKE :foo")
            ->setParameter('foo', '%'.$word.'%')
            ->orderBy('a.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
