<?php declare(strict_types=1);

namespace App\Token\Application;

use App\Token\Api\DTO\PasswordChangeTokenDto;
use App\Token\Api\PasswordChangeTokenServiceInterface;
use App\Token\Domain\Entity\Token;
use App\Token\Domain\Entity\TokenInterface;
use App\Token\Domain\Repository\TokenRepositoryInterface;

readonly final class PasswordChangeTokenService implements PasswordChangeTokenServiceInterface
{
    public function __construct(
        private TokenRepositoryInterface $tokenRepository,
    ) {
    }

    public function insert(PasswordChangeTokenDto $passwordChangeTokenDto): void
    {
        $token = new Token(
            $passwordChangeTokenDto->id,
            $passwordChangeTokenDto->accountId,
            $passwordChangeTokenDto->tokenType,
            $passwordChangeTokenDto->token,
            $passwordChangeTokenDto->createdAt,
        );
        $this->tokenRepository->insert($token);
    }

    public function findOneByToken(string $token): PasswordChangeTokenDto
    {
        $token = $this->tokenRepository->findOneByToken($token);

        return $this->toDto($token);
    }

    public function deleteById(int $id): void
    {
        $this->tokenRepository->deleteById($id);
    }

    private function toDto(TokenInterface $token): PasswordChangeTokenDto
    {
        return new PasswordChangeTokenDto(
            $token->id,
            $token->accountId,
            $token->tokenType,
            $token->token,
            $token->createdAt,
        );
    }
}
