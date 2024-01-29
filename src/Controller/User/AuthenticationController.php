<?php

namespace App\Controller;

use App\Business\TokenBusiness;
use App\Entity\TokenType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AuthenticationController extends AbstractController
{
    #[Route(path: '/login', name: 'app_authentication_login', methods: [Request::METHOD_POST])]
    public function login(): void
    {
        throw new \RuntimeException('Should never be called');
    }

    #[Route(path: '/logout', name: 'app_authentication_logout')]
    public function logout(
        TokenBusiness $tokenBusiness,
    ): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new \RuntimeException('Should never happen');
        }

        $tokenBusiness->deleteUserToken($user, TokenType::AUTHENTICATION);

        return $this->json([
            'result' => true,
        ]);
    }
}