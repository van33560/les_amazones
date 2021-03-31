<?php

namespace App\Repository;


use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }


    public function searchByTerm($search)
   {
        $queryBuilder = $this->createQueryBuilder('a');

        $query = $queryBuilder
            ->select('a')
            ->Where('a.title LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery();

       return $query->getResult();

   }










    ///**
     //* @route("/search",name="search_articles")
     //* @param $search
     //* @return int|mixed|string
     //*/

    //public function searchInTitle($search) {
        //ecrire une requete sql qui utilise le like pour trouver
        //dans tous les articles ceux qui ont le mot recherché dans les titres
       // $qb = $this->createQueryBuilder('a');

        //$query = $qb->select('a')
           // ->Where('a.title LIKE :search')
            //->setParameter('search', '%'.$search.'%')
            //->getQuery();

        //return $query->getResult();
    //}

    // /**
    //  * @return Articles[] Returns an array of Articles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Articles
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */



}
