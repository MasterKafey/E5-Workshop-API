<?php

namespace App\Repository;

use App\Form\Model\Item\ListItemModel;
use Doctrine\ORM\EntityRepository;

class ItemRepository extends EntityRepository
{
    public function deleteUnlinkedItems(): void
    {
        $queryBuilder = $this->createQueryBuilder('item')->delete();

        $queryBuilder->where(
            $queryBuilder->expr()->andX(
                'item.categories is empty',
                'item.screens is empty',
            )
        );


        $queryBuilder->getQuery()->execute();
    }

    public function search(ListItemModel $model): array
    {
        $queryBuilder = $this->createQueryBuilder('item');

        $and = [];
        $parameters = [];

        if (null !== $model->getType()) {
            $and[] = $queryBuilder->expr()->isInstanceOf('item', ':type');
            $parameters['type'] = $model->getType();
        }

        if (!empty($and)) {
            $queryBuilder
                ->where(
                    $queryBuilder->expr()->andX(
                        ...$and
                    )
                );
        }

        if (!empty($parameters)) {
            $queryBuilder->setParameters($parameters);
        }

        $queryBuilder
            ->setMaxResults($model->getMax())
            ->setFirstResult(($model->getPage() - 1) * $model->getMax());

        return $queryBuilder->getQuery()->getResult();
    }
}