<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Token;

use Firebase\JWT\JWT;
use ownHackathon\App\Account\Identity\Domain\AccountAccessAuthInterface;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Account\Identity\Domain\Exception\AccountNotFoundException;
use ownHackathon\App\Account\Identity\Domain\Exception\InvalidRefreshTokenException;
use ownHackathon\App\Account\Identity\Domain\Exception\SecurityBreachException;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use ownHackathon\App\Account\Identity\DTO\Client\ClientIdentification;
use ownHackathon\App\Account\Identity\DTO\Token\AccessToken;
use ownHackathon\App\Account\Identity\DTO\Token\JwtTokenConfig;
use ownHackathon\App\Account\Identity\DTO\Token\RefreshToken;
use ownHackathon\Core\Shared\Trait\JwtTokenTrait;

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
        $accountAccessAuth = $this->accessAuthRepository->findOneByRefreshToken($refreshToken->refreshToken);
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
        $account = $this->accountRepository->findOneById($accountAccessAuth->accountId);
        if (!$account instanceof AccountInterface) {
            throw new AccountNotFoundException(
                accountId: $accountAccessAuth->accountId,
                accessAuthId: $accountAccessAuth->id
            );
        }

        return $account;
    }
}
