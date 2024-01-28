<?php

namespace App\Repository;

use App\Entity\TokenType;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getUserFromToken(string $token, TokenType $type): ?User
    {
        $queryBuilder = $this->createQueryBuilder('user');

        $queryBuilder->join('user.tokens', 'token');

        $queryBuilder
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('token.type', ':type'),
                    $queryBuilder->expr()->eq('token.value', ':value'),
                    $queryBuilder->expr()->gt('token.expiresAt', ':now')
                )
            )
        ;

        $queryBuilder
            ->setParameter('type', $type)
            ->setParameter('value', $token)
            ->setParameter('now', new \DateTime())
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}