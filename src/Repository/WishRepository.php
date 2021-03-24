<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wish[]    findAll()
 * @method Wish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

    public function findWishList(int $page = 1, int $maxResults) : ?array
    {
        $offset = ($page - 1) * $maxResults;

        $queryBuilder = $this->createQueryBuilder('w');

        $queryBuilder->andWhere('w.is_published = true');

        $queryBuilder->select('COUNT(w)');
        $countQuery = $queryBuilder->getQuery();
        $resultCount = $countQuery->getSingleScalarResult();

        $queryBuilder->addOrderBy('w.date_created', 'DESC');
        $queryBuilder->setMaxResults($maxResults);
        $queryBuilder->setFirstResult($offset);

        $queryBuilder->select('w');
        $query = $queryBuilder->getQuery();

        $result = $query->getResult();

        return [
            "resultCount" => $resultCount,
            "result" => $result
        ];
    }
}
