<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class JwtAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly string $secretKey
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $authHeader = $request->headers->get(key: 'Authorization');
        if (!$authHeader || !str_starts_with(haystack: $authHeader, needle: 'Bearer ')) {
            throw new AuthenticationException('Invalid token format');
        }

        $token = substr(string: $authHeader, offset: 7);

        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));

            if ($decoded->type !== 'access') {
                throw new AuthenticationException('Invalid token type');
            }

            return new SelfValidatingPassport(
                new UserBadge($decoded->sub, function($userId) use ($decoded) {
                    return new JwtUser($userId, $decoded->roles ?? ['ROLE_USER']);
                })
            );

        } catch (\Exception $e) {
            throw new AuthenticationException('Invalid token: ' . $e->getMessage());
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(
            data: [
                'error' => $exception->getMessage()
            ],
            status: Response::HTTP_UNAUTHORIZED,
        );
    }
}
