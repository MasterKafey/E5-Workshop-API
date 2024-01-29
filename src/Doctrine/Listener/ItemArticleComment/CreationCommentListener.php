<?php

namespace App\Doctrine\Listener\ItemArticleComment;

use App\Entity\ItemArticleComment;
use Symfony\Component\HttpFoundation\RequestStack;

class CreationCommentListener
{
    public function __construct(
        private readonly RequestStack $requestStack
    )
    {

    }

    public function prePersist(ItemArticleComment $comment): void
    {
        $comment
            ->setIpAddress($this->requestStack->getMainRequest()?->getClientIp())
            ->setCreatedAt(new \DateTimeImmutable())
        ;
    }


}