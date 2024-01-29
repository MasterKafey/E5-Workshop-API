<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\TokenType;
use App\Form\Type\User\ForgotPasswordRequestType;
use App\Form\Type\User\ForgotPasswordType;
use App\Utils\FormHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class ForgotPasswordController extends AbstractController
{
    #[Route('/forgot-password/request', methods: [Request::METHOD_POST])]
    public function forgotPasswordRequest(Request $request): Response
    {
        $form = $this->createForm(ForgotPasswordRequestType::class);
        $form->submit($request->toArray());

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'result' => false,
                'errors' => FormHelper::getErrors($form),
            ]);
        }

        

        return $this->json([
            'result' => true,
        ]);
    }

    #[Route('/forgot-password/success', name: 'app_user_forgot_password_success', methods: [Request::METHOD_GET])]
    public function forgotPasswordSuccess(): Response
    {
        return $this->render('Page/User/forgot_password_success.html.twig');
    }

    #[Route('/forgot-password/{value}')]
    public function forgotPassword(Request $request, Token $token, EntityManagerInterface $entityManager): Response
    {
        if ($token->getType() !== TokenType::FORGOT_PASSWORD || $token->getExpiresAt() <= new \DateTime()) {
            throw new NotFoundHttpException();
        }

        $user = $token->getUser();
        $form = $this
            ->createForm(ForgotPasswordType::class, $user)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->remove($token);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_forgot_password_success');
        }

        return $this->render('Page/User/forgot_password.html.twig');
    }

}