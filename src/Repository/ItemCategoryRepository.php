<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\ORM\EntityRepository;

class ItemCategoryRepository extends EntityRepository
{

    public function getParentItem(Item $item)
    {
        $queryBuilder = $this->createQueryBuilder('item_category');

        $queryBuilder
            ->where(':item MEMBER OF item_category.children')
            ->setParameter('item', $item)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}