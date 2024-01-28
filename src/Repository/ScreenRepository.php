<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

class ScreenRepository extends EntityRepository
{
    public function getParentScreen(Item $item): array
    {
        $queryBuilder = $this->createQueryBuilder('screen');


        $queryBuilder
            ->where(':item MEMBER OF screen.items')
            ->setParameter('item', $item)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}