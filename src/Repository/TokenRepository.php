<?php

namespace App\Repository;

use App\Entity\TokenType;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class TokenRepository extends EntityRepository
{
    public function delete(User $user, TokenType $type): void
    {
        $queryBuilder = $this->createQueryBuilder('token')->delete();

        $queryBuilder->where(
            $queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq('token.user', ':user'),
                $queryBuilder->expr()->eq('token.type', ':type')
            )
        );

        $queryBuilder
            ->setParameter('user', $user)
            ->setParameter('type', $type)
        ;

        $queryBuilder->getQuery()->execute();
    }
}