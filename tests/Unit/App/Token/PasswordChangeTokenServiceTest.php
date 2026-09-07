<?php declare(strict_types=1);

namespace Tests\Unit\App\Token;

use App\Token\Api\DTO\PasswordChangeTokenDto;
use App\Token\Api\Enum\TokenType;
use App\Token\Api\PasswordChangeTokenServiceInterface;
use App\Token\Application\PasswordChangeTokenService;
use App\Token\Domain\Entity\Token;
use App\Token\Domain\Entity\TokenInterface;
use App\Token\Domain\Repository\TokenRepositoryInterface;
use Core\SharedKernel\Domain\Exception\EmptyResultException;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

use function expect;
use function test;

function passwordChangeTokenServiceToken(): Token
{
    return new Token(
        id: 1,
        accountId: 2,
        tokenType: TokenType::EMail,
        token: Uuid::fromString('019becbe-f952-7b82-82fa-f41f8ae24599'),
        createdAt: new DateTimeImmutable('2024-01-01 10:00:00'),
    );
}

test('password change token service inserts a token via the repository', function (): void {
    $token = passwordChangeTokenServiceToken();
    $dto = new PasswordChangeTokenDto($token->id, $token->accountId, $token->tokenType, $token->token, $token->createdAt);

    $repository = $this->createMock(TokenRepositoryInterface::class);
    $repository->expects($this->once())->method('insert')->with($this->callback(
        static fn (TokenInterface $value): bool => $value->id === $dto->id && $value->tokenType === $dto->tokenType,
    ))->willReturn(1);

    $service = new PasswordChangeTokenService($repository);
    $service->insert($dto);

    expect($service)->toBeInstanceOf(PasswordChangeTokenServiceInterface::class);
});

test('password change token service maps a found token to its dto', function (): void {
    $token = passwordChangeTokenServiceToken();

    $repository = $this->createMock(TokenRepositoryInterface::class);
    $repository->expects($this->once())->method('findOneByToken')
        ->with($token->token->toString())
        ->willReturn($token);

    $service = new PasswordChangeTokenService($repository);
    $dto = $service->findOneByToken($token->token->toString());

    expect($dto)->toBeInstanceOf(PasswordChangeTokenDto::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->accountId)->toBe(2)
        ->and($dto->tokenType)->toBe(TokenType::EMail)
        ->and($dto->token)->toBeInstanceOf(UuidInterface::class)
        ->and($dto->token->toString())->toBe('019becbe-f952-7b82-82fa-f41f8ae24599')
        ->and($dto->createdAt->format('Y-m-d H:i:s'))->toBe('2024-01-01 10:00:00');
});

test('password change token service propagates unknown token errors', function (): void {
    $repository = $this->createMock(TokenRepositoryInterface::class);
    $repository->expects($this->once())->method('findOneByToken')->willThrowException(new EmptyResultException());

    $service = new PasswordChangeTokenService($repository);

    expect(fn () => $service->findOneByToken('missing'))->toThrow(EmptyResultException::class);
});

test('password change token service deletes a token via the repository', function (): void {
    $repository = $this->createMock(TokenRepositoryInterface::class);
    $repository->expects($this->once())->method('deleteById')->with(1)->willReturn(true);

    $service = new PasswordChangeTokenService($repository);
    $service->deleteById(1);

    expect($service)->toBeInstanceOf(PasswordChangeTokenServiceInterface::class);
});