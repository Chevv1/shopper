<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Service;

use App\Identity\Application\ReadModel\AuthTokenReadModel;
use App\Identity\Domain\Entity\User\RefreshToken;
use App\Identity\Domain\Entity\User\Roles;
use App\Identity\Domain\Entity\User\UserId;
use App\Identity\Domain\Service\TokenGeneratorInterface;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final readonly class FirebaseJwtTokenGenerator implements TokenGeneratorInterface
{
    private const string ALGORITHM = 'HS256';
    private const string KEY_TYPE_ACCESS = 'access';
    private const string KEY_TYPE_REFRESH = 'refresh';
    private const array DEFAULT_ROLES = [
        'ROLE_USER',
    ];

    public function __construct(
        private string $secretKey,
        private string $refreshSecretKey,
        private int $accessTokenTtl = 3600,      // 1 hour
        private int $refreshTokenTtl = 2592000   // 30 days
    ) {}

    public function generate(UserId $userId, ?Roles $roles = null): AuthTokenReadModel
    {
        $now = time();

        return new AuthTokenReadModel(
            accessToken: JWT::encode(
                payload: [
                    'iat' => $now,
                    'exp' => $now + $this->accessTokenTtl,
                    'sub' => $userId->value(),
                    'roles' => $roles->value(),
                    'type' => self::KEY_TYPE_ACCESS,
                ],
                key: $this->secretKey,
                alg: self::ALGORITHM,
            ),
            refreshToken: JWT::encode(
                payload: [
                    'iat' => $now,
                    'exp' => $now + $this->refreshTokenTtl,
                    'sub' => $userId->value(),
                    'type' => self::KEY_TYPE_REFRESH,
                ],
                key: $this->refreshSecretKey,
                alg: self::ALGORITHM,
            ),
            expiresIn: $this->accessTokenTtl
        );
    }

    public function refresh(RefreshToken $refreshToken): AuthTokenReadModel
    {
        try {
            $decoded = JWT::decode(
                jwt: $refreshToken->value(),
                keyOrKeyArray: new Key(
                    keyMaterial: $this->refreshSecretKey,
                    algorithm: self::ALGORITHM,
                ),
            );

            if (!isset($decoded->type) || $decoded->type !== self::KEY_TYPE_REFRESH) {
                throw new \InvalidArgumentException('Invalid token type');
            }

            if (empty($decoded->sub)) {
                throw new \InvalidArgumentException('Token does not contain user identifier');
            }

            $roles = isset($decoded->roles) && is_array($decoded->roles)
                ? $decoded->roles
                : self::DEFAULT_ROLES;

            return $this->generate(
                userId: new UserId($decoded->sub),
                roles: new Roles($roles),
            );
        } catch (Exception $e) {
            throw new \InvalidArgumentException('Invalid refresh token: ' . $e->getMessage());
        }
    }
}
