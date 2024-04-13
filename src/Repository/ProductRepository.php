<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 6;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getProductPaginatorFiltered(int $page, $sortDirection, $sortBy): Paginator
    {
        $offset = ($page-1)*self::PAGINATOR_PER_PAGE;
        $query = $this->createQueryBuilder('c')
            ->setFirstResult($offset)
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->orderBy('c.'.$sortBy, $sortDirection)
            ->getQuery();
        return new Paginator($query, false);
    }

}
