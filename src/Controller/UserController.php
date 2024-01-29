<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Serializer;

#[Route(path: '/user')]
class UserController extends AbstractController
{
    #[Route(path: '/info')]
    public function getUserInformation(Serializer $serializer): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new \RuntimeException('Should never be called');
        }

        return $this->json([
            'result' => true,
            'data' => $serializer->normalize($user),
        ]);
    }
}