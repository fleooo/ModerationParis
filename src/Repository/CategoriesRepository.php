<?php


namespace App\Repository;

use App\Entity\Categories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categories::class);
    }

    public function findParentById(int $id): ?Categories
    {
        return $this->find($id);
    }

    public function findParentByName(string $name): ?Categories
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function findChildrenByParent(Categories $parent): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.parent = :parent')
            ->setParameter('parent', $parent)
            ->orderBy('c.categoryOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}