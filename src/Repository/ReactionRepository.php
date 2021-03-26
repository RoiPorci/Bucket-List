<?php

namespace App\Repository;

use App\Entity\Reaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reaction[]    findAll()
 * @method Reaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reaction::class);
    }


    public function findWishReactions(int $idWish, int $page = 1, int $maxResults): array
    {
        $offset = ($page - 1) * $maxResults;

        $queryBuilder = $this->createQueryBuilder('r');

        $queryBuilder->andWhere("r.wish = :id_wish");
        $queryBuilder->setParameter(":id_wish", $idWish);

        $queryBuilder->select('COUNT(r)');
        $countQuery = $queryBuilder->getQuery();

        $resultCount = $countQuery->getSingleScalarResult();

        $queryBuilder->addOrderBy('r.date_created', 'DESC');
        $queryBuilder->setMaxResults($maxResults);
        $queryBuilder->setFirstResult($offset);

        $queryBuilder->select('r');

        $query = $queryBuilder->getQuery();

        $result = $query->getResult();

        return [
            "resultCount" => $resultCount,
            "result" => $result
        ];
    }
    // /**
    //  * @return Reaction[] Returns an array of Reaction objects
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
    public function findOneBySomeField($value): ?Reaction
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
