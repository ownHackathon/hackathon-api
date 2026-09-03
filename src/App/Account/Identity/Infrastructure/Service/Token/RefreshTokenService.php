<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Service\Token;

use App\Account\Identity\Domain\AccountAccessAuthInterface;
use App\Account\Identity\Domain\AccountInterface;
use App\Account\Identity\Domain\Exception\AccountNotFoundException;
use App\Account\Identity\Domain\Exception\InvalidRefreshTokenException;
use App\Account\Identity\Domain\Exception\SecurityBreachException;
use App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use App\Account\Identity\DTO\Client\ClientIdentification;
use App\Account\Identity\DTO\Token\AccessToken;
use App\Account\Identity\DTO\Token\JwtTokenConfig;
use App\Account\Identity\DTO\Token\RefreshToken;
use App\Token\Infrastructure\Token\JwtTokenTrait;
use Core\SharedKernel\Domain\Exception\EmptyResultException;
use Firebase\JWT\JWT;

use function time;

// phpcs:ignore SlevomatCodingStandard.Classes.RequireAbstractOrFinal.ClassNeitherAbstractNorFinal -- wird in Unit-Tests direkt gemockt
readonly class RefreshTokenService
{
    use JwtTokenTrait;

    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private AccountAccessAuthRepositoryInterface $accessAuthRepository,
        private AccessTokenService $accessTokenService,
        private JwtTokenConfig $config,
    ) {
    }

    public function generate(ClientIdentification $clientIdentification): RefreshToken
    {
        $now = time();

        $payload = [
            'iss' => $this->config->iss,
            'aud' => $this->config->aud,
            'iat' => $now,
            'exp' => $now + $this->config->duration,
            'ident' => $clientIdentification->identificationHash,
        ];

        $token = JWT::encode($payload, $this->config->key, $this->config->algorithmus);

        return RefreshToken::fromString($token);
    }

    /**
     * @throws InvalidRefreshTokenException
     * @throws SecurityBreachException
     * @throws AccountNotFoundException
     */
    public function refresh(
        RefreshToken $refreshToken,
        ClientIdentification $client,
    ): AccessToken {
        $accountAccessAuth = $this->validateTokenAndClient($refreshToken, $client);

        $account = $this->findAccountOrThrow($accountAccessAuth);

        return $this->accessTokenService->generate($account->uuid);
    }

    /**
     * @throws InvalidRefreshTokenException
     * @throws SecurityBreachException
     */
    private function validateTokenAndClient(
        RefreshToken $refreshToken,
        ClientIdentification $client,
    ): AccountAccessAuthInterface {
        try {
            $accountAccessAuth = $this->accessAuthRepository->findOneByRefreshToken($refreshToken->refreshToken);
        } catch (EmptyResultException) {
            throw new InvalidRefreshTokenException($refreshToken->refreshToken);
        }
        if ($accountAccessAuth->clientIdentHash !== $client->identificationHash) {
            throw new SecurityBreachException(
                expectedClientHash: $accountAccessAuth->clientIdentHash,
                actualClientHash: $accountAccessAuth->userAgent,
                expectedUserAgent: $client->identificationHash,
                actualUserAgent: $client->clientIdentificationData->userAgent,
            );
        }

        return $accountAccessAuth;
    }

    /**
     * @throws AccountNotFoundException
     */
    private function findAccountOrThrow(AccountAccessAuthInterface $accountAccessAuth): AccountInterface
    {
        try {
            $account = $this->accountRepository->findOneById($accountAccessAuth->accountId);
        } catch (EmptyResultException) {
            throw new AccountNotFoundException(
                accountId: $accountAccessAuth->accountId,
                accessAuthId: $accountAccessAuth->id,
            );
        }

        return $account;
    }
}
