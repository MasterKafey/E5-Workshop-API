<?php

namespace App\Serializer\Normalizer;

use App\Entity\ItemArticleComment;
use App\Security\Voter\ItemArticleCommentVoter;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ItemArticleCommentNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly ObjectNormalizer $objectNormalizer
    )
    {

    }

    public function normalize(mixed $object, string $format = null, array $context = []): float|array|\ArrayObject|bool|int|string|null
    {
        $data = $this->objectNormalizer->normalize($object, $format, $context);
        $data['editable'] = $this->authorizationChecker->isGranted(ItemArticleCommentVoter::UPDATE, $object);

        return $data;
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof ItemArticleComment;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            ItemArticleComment::class => true,
        ];
    }
}