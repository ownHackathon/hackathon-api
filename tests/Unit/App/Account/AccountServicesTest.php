<?php declare(strict_types=1);

namespace Tests\Unit\App\Account;

use DateTimeImmutable;
use App\Account\Identity\Domain\Account;
use App\Account\Identity\Domain\AccountAccessAuth;
use App\Account\Identity\Domain\Exception\PasswordMismatchException;
use App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use App\Account\Identity\Infrastructure\Service\Account\AccountService;
use App\Account\Identity\Infrastructure\Service\Token\PasswordTokenService;
use App\Account\Identity\DTO\Token\RefreshToken;
use App\Mailing\Domain\EmailType;
use App\Token\Domain\Repository\TokenRepositoryInterface;
use Core\SharedKernel\Domain\Exception\EmptyResultException;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

function serviceAccount(): Account
{
    return new Account(7, Uuid::uuid4(), 'Alice', 'hash', new EmailType('alice@example.com'), new DateTimeImmutable(), null);
}

test('account service handles availability, password creation and activity updates', function (): void {
    $accountRepository = $this->createMock(AccountRepositoryInterface::class);
    $throws = true;
    $accountRepository->expects($this->exactly(2))->method('findOneByEmail')->willReturnCallback(
        static function () use (&$throws): Account {
            if ($throws) {
                $throws = false;
                throw new EmptyResultException();
            }

            return serviceAccount();
        },
    );
    $accountRepository->expects($this->once())->method('update')->with($this->callback(
        static fn (Account $account): bool => $account->lastActionAt instanceof DateTimeImmutable,
    ))->willReturn(true);
    $service = new AccountService(
        $accountRepository,
        $this->createMock(AccountAccessAuthRepositoryInterface::class),
        $this->createMock(TokenRepositoryInterface::class),
        $this->createMock(PasswordTokenService::class),
        $this->createMock(UuidFactoryInterface::class),
        $this->createMock(LoggerInterface::class),
    );
    $email = new EmailType('alice@example.com');

    expect($service->isEmailAvailable($email))->toBeTrue()
        ->and($service->isEmailAvailable($email))->toBeFalse()
        ->and(password_verify('secret', $service->cryptPassword('secret')))->toBeTrue();
    $service->updateLastAction(serviceAccount());
});

test('account service rejects unknown or foreign logout tokens', function (): void {
    $account = serviceAccount();
    $authRepository = $this->createMock(AccountAccessAuthRepositoryInterface::class);
    $foreignAuth = new AccountAccessAuth(
        1,
        99,
        'web',
        'refresh',
        'agent',
        'hash',
        new DateTimeImmutable(),
    );
    $authRepository->expects($this->exactly(2))->method('findOneByRefreshToken')
        ->willReturnCallback(
            static function () use ($foreignAuth): AccountAccessAuth {
                static $call = 0;
                $call++;
                if ($call === 1) {
                    throw new EmptyResultException();
                }

                return $foreignAuth;
            },
        );
    $service = new AccountService(
        $this->createMock(AccountRepositoryInterface::class),
        $authRepository,
        $this->createMock(TokenRepositoryInterface::class),
        $this->createMock(PasswordTokenService::class),
        $this->createMock(UuidFactoryInterface::class),
        $this->createMock(LoggerInterface::class),
    );
    expect(function () use ($service, $account): void {
        $service->logout($account, new RefreshToken('refresh'));
    })
        ->toThrow(\Core\Http\Exception\HttpUnauthorizedException::class);
    expect(function () use ($service, $account): void {
        $service->logout($account, new RefreshToken('refresh'));
    })
        ->toThrow(\Core\Http\Exception\HttpUnauthorizedException::class);
});

test('password mismatch exception keeps its email', function (): void {
    expect((new PasswordMismatchException('alice@example.com'))->email)->toBe('alice@example.com');
});
