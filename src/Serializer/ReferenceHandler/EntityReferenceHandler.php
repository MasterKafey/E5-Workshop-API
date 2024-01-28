<?php

namespace App\Serializer\ReferenceHandler;

class EntityReferenceHandler
{
    public function __invoke($object): ?int
    {
        return null === $object->getId() ? null : intval($object->getId());
    }
}