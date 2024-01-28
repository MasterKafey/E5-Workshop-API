<?php

namespace App\Security\Authenticator;

use App\Business\TokenBusiness;
use App\Entity\Token;
use App\Entity\TokenType;
use App\Entity\User;
use App\Form\Model\Authentication\LoginModel;
use App\Form\Type\Authentication\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class CredentialsAuthenticator extends AbstractAuthenticator
{
    public const LOGIN_ROUTE = 'app_authentication_login';

    public function __construct(
        private readonly TokenBusiness          $tokenBusiness,
        private readonly EntityManagerInterface $entityManager,
        private readonly FormFactoryInterface   $formFactory
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->isMethod(Request::METHOD_POST) && $request->attributes->get('_route') === self::LOGIN_ROUTE;
    }

    public function authenticate(Request $request): Passport
    {
        $model = new LoginModel();
        $form = $this->formFactory->create(LoginType::class, $model)->submit($request->toArray());

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new AuthenticationCredentialsNotFoundException($form->getErrors(true)->current()->getMessage());
        }

        return new Passport(new UserBadge($model->getEmail()), new PasswordCredentials($model->getPassword()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('Should never happen');
        }

        $this->tokenBusiness->deleteUserToken($user, TokenType::AUTHENTICATION);
        $authenticationToken = $this->tokenBusiness->createNewUserToken($user, TokenType::AUTHENTICATION);
        $this->entityManager->persist($authenticationToken);
        $this->entityManager->flush();

        return new JsonResponse([
            'result' => true,
            'data' => [
                'token' => $authenticationToken->getValue(),
            ],
        ]);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'result' => false,
            'errors' => [
                $exception->getMessage(),
            ]
        ]);
    }
}