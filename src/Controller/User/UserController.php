<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\TokenType;
use App\Entity\User;
use App\Form\Type\User\ForgotPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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