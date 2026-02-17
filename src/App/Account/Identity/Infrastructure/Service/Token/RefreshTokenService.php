<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Token;

use Exdrals\Core\Shared\Trait\JwtTokenTrait;
use Firebase\JWT\JWT;
use Exdrals\Identity\Domain\AccountAccessAuthInterface;
use Exdrals\Identity\Domain\AccountInterface;
use Exdrals\Identity\Domain\Exception\AccountNotFoundException;
use Exdrals\Identity\Domain\Exception\InvalidRefreshTokenException;
use Exdrals\Identity\Domain\Exception\SecurityBreachException;
use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Identity\DTO\Token\AccessToken;
use Exdrals\Identity\DTO\Token\JwtTokenConfig;
use Exdrals\Identity\DTO\Token\RefreshToken;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountAccessAuthRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepositoryInterface;

use function time;

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
        ClientIdentification $client
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
        ClientIdentification $client
    ): AccountAccessAuthInterface {
        $accountAccessAuth = $this->accessAuthRepository->findByRefreshToken($refreshToken->refreshToken);
        if (!$accountAccessAuth instanceof AccountAccessAuthInterface) {
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
        $account = $this->accountRepository->findById($accountAccessAuth->accountId);
        if (!$account instanceof AccountInterface) {
            throw new AccountNotFoundException(
                accountId: $accountAccessAuth->accountId,
                accessAuthId: $accountAccessAuth->id
            );
        }

        return $account;
    }
}
