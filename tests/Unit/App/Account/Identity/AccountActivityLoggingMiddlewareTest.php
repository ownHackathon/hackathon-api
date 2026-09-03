<?php declare(strict_types=1);

namespace Tests\Unit\App\Account\Identity;

use App\Account\Identity\Application\Port\ActivityLoggerInterface;
use App\Account\Identity\Domain\Account;
use App\Account\Identity\Domain\AccountInterface;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\DTO\Client\ClientIdentification;
use App\Account\Identity\DTO\Client\ClientIdentificationData;
use App\Account\Identity\Middleware\Account\AccountActivityLoggingMiddleware;
use App\Mailing\Domain\EmailType;
use DateTimeImmutable;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

test('account activity middleware logs a guest interaction with masked context', function (): void {
    $context = null;
    $logger = $this->createMock(ActivityLoggerInterface::class);
    $logger->expects($this->once())->method('log')
        ->willReturnCallback(
            static function (mixed $level, mixed $message, array $ctx = []) use (&$context): void {
                $context = $ctx;
            },
        );

    $client = ClientIdentification::create(
        new ClientIdentificationData('fingerprint', 'Mozilla/5.0 (X11; Linux) Chrome/120'),
        'client-hash',
    );

    $request = (new ServerRequest([], [], 'http://example.test/api/ping', 'GET'))
        ->withAttribute(ClientIdentification::class, $client);

    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->method('handle')->willReturn(new JsonResponse(['ok' => true], 200));

    $response = (new AccountActivityLoggingMiddleware($logger))->process($request, $handler);

    expect($response)->toBeInstanceOf(ResponseInterface::class);
    expect($context)->not->toBeNull()
        ->and($context['guest'])->toBeTrue()
        ->and($context['status'])->toBe(200)
        ->and($context['method'])->toBe('GET')
        ->and($context['clientIdentHash'])->toBe('client-hash')
        ->and($context['userAgent'])->toBe('browser');
});

test('account activity middleware logs an authenticated interaction with account ids', function (): void {
    $messages = [];
    $logger = $this->createMock(ActivityLoggerInterface::class);
    $logger->expects($this->once())->method('log')
        ->willReturnCallback(
            static function (mixed $level, mixed $message, array $ctx = []) use (&$messages): void {
                $messages[] = ['level' => $level, 'message' => $message, 'context' => $ctx];
            },
        );

    $account = new Account(
        7,
        Uuid::uuid4(),
        'Alice',
        'hash',
        new EmailType('alice@example.com'),
        new DateTimeImmutable(),
        null,
    );

    $request = (new ServerRequest([], [], 'http://example.test/api/ping', 'POST'))
        ->withAttribute(AccountInterface::AUTHENTICATED, $account)
        ->withAttribute(ClientIdentification::class, ClientIdentification::create(
            new ClientIdentificationData('fingerprint', 'curl/8.0.0'),
            'client-hash',
        ));

    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->method('handle')->willReturn(new JsonResponse([], 400));

    (new AccountActivityLoggingMiddleware($logger))->process($request, $handler);

    $entry = $messages[0];
    expect($entry['context']['guest'])->toBeFalse()
        ->and($entry['context']['accountId'])->toBe(7)
        ->and($entry['context']['accountUuid'])->toBeString()
        ->and($entry['context']['status'])->toBe(400)
        ->and($entry['context']['userAgent'])->toBe('curl')
        ->and($entry['message'])->toBe(IdentityLogMessage::ACTIVITY_INTERACTION_WARNING);
});
