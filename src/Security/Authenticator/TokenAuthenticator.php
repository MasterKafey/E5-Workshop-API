<?php

namespace App\Security\Authenticator;

use App\Business\TokenBusiness;
use App\Entity\TokenType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly TokenBusiness $tokenBusiness,
        private readonly EntityManagerInterface $entityManager
    )
    {

    }

    public function supports(Request $request): ?bool
    {
        return $this->tokenBusiness->getBearerTokenFromRequest($request) !== null;
    }

    public function authenticate(Request $request): Passport
    {
        $tokenValue = $this->tokenBusiness->getBearerTokenFromRequest($request);

        $user = $this->tokenBusiness->getUserFromToken($tokenValue, TokenType::AUTHENTICATION);

        if (null === $user) {
            throw new BadCredentialsException('Invalid token');
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier(), function() use ($user) {return $user;}));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            throw new \RuntimeException('Should never happen');
        }

        $authenticationToken = $this->tokenBusiness->getTokenFromUser($user, TokenType::AUTHENTICATION);
        $this->tokenBusiness->refreshExpirationDate($authenticationToken);
        $this->entityManager->flush();

        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'result' => false,
            'errors' => [
                $exception->getMessage(),
            ],
        ]);
    }
}