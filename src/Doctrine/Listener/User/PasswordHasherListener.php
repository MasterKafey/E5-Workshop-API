<?php

namespace App\Doctrine\Listener\User;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherListener
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    )
    {

    }

    public function prePersist(User $user): void
    {
        $this->hashUserPassword($user);
    }

    public function preUpdate(User $user): void
    {
        $this->hashUserPassword($user);
    }

    private function hashUserPassword(User $user): void
    {
        if (null === $user->getPlainPassword()) {
            return;
        }

        $password = $this->hasher->hashPassword($user, $user->getPlainPassword());
        $user->setPassword($password);
    }
}