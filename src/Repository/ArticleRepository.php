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

    /*création d'une méthode pour rechercher en fonction du climat et de l'activité*/
    public function searchBySelection($selectionActivity, $selectionClimat)
    {
        /* instanciation du créateur de requête grâce à une méthode connue de SF*/
        $queryBuilder = $this->createQueryBuilder('article');

        $query = $queryBuilder
            ->select('article')
            ->where("article.filter_activities = :selectionActivity")
            ->andWhere("article.filter_climat = :selectionClimat")
            /* sécurisation de la requête grâce à setParameters et après avoir vérification,
               et exécution de la requête SQL */
            ->setParameters([
                'selectionActivity' => $selectionActivity,
                'selectionClimat' => $selectionClimat
            ])
            ->getQuery();
        return $query->getResult();

    }

    /* création d'une méthode pour rechercher en fonction du continent*/
    public function searchByContinent($selectionContinent)
    {
        $queryBuilder = $this->createQueryBuilder('article');

        $query = $queryBuilder
            ->select('article')
            ->where("article.filter_continent = :selectionContinent")
            ->setParameters(['selectionContinent' => $selectionContinent])
            ->getQuery();
        return $query->getResult();

    }

    /* création d'une méthode pour afficher un article aléatoirement
    public function randomArticle($random)
    {
        $queryBuilder = $this->createQueryBuilder('random');

        $query = $queryBuilder
            ->select('random')
            ->from(Article::class, 'a')
            ->orderBy('RAND()')
            ->setMaxResults(5)
            ->getQuery();

        return $query->getResult();

    }*/

    // /**
    //  * @return Article[] Returns an array of Article objects
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
    public function findOneBySomeField($value): ?Article
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
