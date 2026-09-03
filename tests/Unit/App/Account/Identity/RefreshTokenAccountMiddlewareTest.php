<?php declare(strict_types=1);

namespace Tests\Unit\App\Account\Identity;

use DateTimeImmutable;
use Laminas\Diactoros\ServerRequest;
use App\Account\Identity\Domain\Account;
use App\Account\Identity\Domain\AccountAccessAuth;
use App\Account\Identity\Domain\AccountAccessAuthInterface;
use App\Account\Identity\Domain\AccountInterface;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use App\Account\Identity\Middleware\Token\RefreshTokenAccountMiddleware;
use App\Mailing\Domain\EmailType;
use Core\Http\Exception\HttpUnauthorizedException;
use Core\SharedKernel\Domain\Exception\EmptyResultException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

function refreshAuth(): AccountAccessAuth
{
    return new AccountAccessAuth(1, 7, 'web', 'refresh', 'agent', 'hash', new DateTimeImmutable());
}

function refreshAccount(): Account
{
    return new Account(7, Uuid::uuid4(), 'Alice', 'hash', new EmailType('alice@example.com'), new DateTimeImmutable(), null);
}

test('refresh account middleware forwards the resolved account', function (): void {
    $auth = refreshAuth();
    $account = refreshAccount();
    $repository = $this->createMock(AccountRepositoryInterface::class);
    $repository->expects($this->once())->method('findOneById')->with($auth->accountId)->willReturn($account);

    $request = (new ServerRequest())->withAttribute(AccountAccessAuthInterface::class, $auth);
    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->expects($this->once())->method('handle')
        ->with($this->callback(
            static fn (ServerRequestInterface $r): bool => $r->getAttribute(AccountInterface::AUTHENTICATED) === $account,
        ))
        ->willReturn($this->createMock(ResponseInterface::class));

    $response = (new RefreshTokenAccountMiddleware($repository))->process($request, $handler);

    expect($response)->toBeInstanceOf(ResponseInterface::class);
});

test('refresh account middleware rejects a missing access auth attribute', function (): void {
    $repository = $this->createMock(AccountRepositoryInterface::class);
    $repository->expects($this->never())->method('findOneById');
    $handler = $this->createMock(RequestHandlerInterface::class);

    expect(fn () => (new RefreshTokenAccountMiddleware($repository))->process(new ServerRequest(), $handler))
        ->toThrow(HttpUnauthorizedException::class);
});

test('refresh account middleware rejects an unknown account', function (): void {
    $auth = refreshAuth();
    $repository = $this->createMock(AccountRepositoryInterface::class);
    $repository->expects($this->once())->method('findOneById')->with($auth->accountId)
        ->willThrowException(new EmptyResultException());
    $handler = $this->createMock(RequestHandlerInterface::class);
    $request = (new ServerRequest())->withAttribute(AccountAccessAuthInterface::class, $auth);

    expect(fn () => (new RefreshTokenAccountMiddleware($repository))->process($request, $handler))
        ->toThrow(HttpUnauthorizedException::class);
});