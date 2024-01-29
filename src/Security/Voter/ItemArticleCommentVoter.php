<?php

namespace App\Security\Voter;

use App\Entity\ItemArticleComment;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ItemArticleCommentVoter extends Voter
{
    public const UPDATE = 'UPDATE';

    public function __construct(
        private readonly RequestStack $requestStack,
    )
    {

    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::UPDATE && $subject instanceof ItemArticleComment;
    }

    /** @param ItemArticleComment $subject */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $ipAddress = $this->requestStack->getMainRequest()?->getClientIp();

        if (null === $ipAddress) {
            return false;
        }

        return $subject->getIpAddress() === $ipAddress;
    }
}