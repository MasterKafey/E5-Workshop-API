<?php

namespace App\Business;

use App\Entity\Token;
use App\Entity\TokenType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TokenBusiness
{
    public const TOKEN_HEADER = 'Authorization';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {

    }

    public function getBearerTokenFromRequest(Request $request): ?string
    {
        if (!$request->headers->has(self::TOKEN_HEADER)) {
            return null;
        }

        $value = $request->headers->get(self::TOKEN_HEADER);

        if (!str_starts_with($value, 'Bearer ')) {
            return null;
        }

        return substr($value, 7);
    }

    public function getUserFromToken(string $token, TokenType $type): ?User
    {
        return $this->entityManager->getRepository(User::class)->getUserFromToken($token, $type);
    }

    public function getTokenFromUser(User $user, TokenType $type): ?Token
    {
        foreach ($user->getTokens() as $token) {
            if ($token->getType() === $type) {
                return $token;
            }
        }

        return null;
    }

    public function createNewUserToken(User $user, TokenType $type): Token
    {
        return (new Token())
            ->setUser($user)
            ->setValue($this->generateRandomValue())
            ->setType($type)
            ->setExpiresAt($this->getExpirationDate($type));
    }

    public function deleteUserToken(User $user, TokenType $type): void
    {
        $this->entityManager->getRepository(Token::class)->delete($user, $type);
    }

    public function refreshExpirationDate(Token $token): void
    {
        $token->setExpiresAt($this->getExpirationDate($token->getType()));
    }

    public function generateRandomValue(int $length = 32): string
    {
        return bin2hex(random_bytes($length/2));
    }

    public function getExpirationDate(TokenType $type): \DateTimeInterface
    {
        $interval = match ($type) {
            TokenType::AUTHENTICATION => 'P1D',
            TokenType::FORGOT_PASSWORD, TokenType::RESET_PASSWORD => 'PT15M',
            default => throw new \InvalidArgumentException('Expiration date is not define for ' . $type->value . ' token type'),
        };

        return (new \DateTime())->add(new \DateInterval($interval));
    }
}