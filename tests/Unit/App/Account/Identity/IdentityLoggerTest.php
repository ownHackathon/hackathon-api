<?php declare(strict_types=1);

namespace Tests\Unit\App\Account\Identity;

use App\Account\Identity\Application\Port\ActivityLoggerInterface;
use App\Account\Identity\Application\Port\EmailHashSaltProviderInterface;
use App\Account\Identity\Application\Port\IdentityLoggerInterface;
use App\Account\Identity\Infrastructure\Factory\ActivityLoggerFactory;
use App\Account\Identity\Infrastructure\Factory\EmailHashSaltProviderFactory;
use App\Account\Identity\Infrastructure\Factory\IdentityLoggerFactory;
use App\Account\Identity\Infrastructure\Logger\ActivityLogger;
use App\Account\Identity\Infrastructure\Logger\IdentityLogger;
use App\Account\Identity\Infrastructure\Provider\EmailHashSaltProvider;
use Core\Observability\LoggerFactory;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

use function expect;
use function sys_get_temp_dir;
use function test;

test('identity logger factory creates IdentityLoggerInterface instance for identity channel', function (): void {
    $path = sys_get_temp_dir() . '/test-identity-logger-' . bin2hex(random_bytes(4));
    mkdir($path);

    $container = $this->createMock(ContainerInterface::class);
    $container->expects($this->once())->method('get')->with('config')->willReturn([
        'logger' => ['path' => $path],
    ]);

    $factory = new IdentityLoggerFactory();
    $logger = $factory($container);

    expect($logger)->toBeInstanceOf(IdentityLoggerInterface::class)
        ->and($logger)->toBeInstanceOf(IdentityLogger::class);
});

test('activity logger factory creates ActivityLoggerInterface instance for account-activity channel', function (): void {
    $path = sys_get_temp_dir() . '/test-activity-logger-' . bin2hex(random_bytes(4));
    mkdir($path);

    $container = $this->createMock(ContainerInterface::class);
    $container->expects($this->once())->method('get')->with('config')->willReturn([
        'logger' => ['path' => $path],
    ]);

    $factory = new ActivityLoggerFactory();
    $logger = $factory($container);

    expect($logger)->toBeInstanceOf(ActivityLoggerInterface::class)
        ->and($logger)->toBeInstanceOf(ActivityLogger::class);
});

test('identity and activity logger classes delegate calls to inner logger', function (): void {
    $innerLogger = $this->createMock(LoggerInterface::class);
    $innerLogger->expects($this->once())->method('log')->with('info', 'test message', ['foo' => 'bar']);

    $identityLogger = new IdentityLogger($innerLogger);
    $identityLogger->info('test message', ['foo' => 'bar']);

    $activityInnerLogger = $this->createMock(LoggerInterface::class);
    $activityInnerLogger->expects($this->once())->method('log')->with('warning', 'activity warning', []);

    $activityLogger = new ActivityLogger($activityInnerLogger);
    $activityLogger->warning('activity warning');
});

test('email hash salt provider returns configured salt', function (): void {
    $provider = new EmailHashSaltProvider('custom-salt-123');
    expect($provider->salt())->toBe('custom-salt-123');

    $container = $this->createMock(ContainerInterface::class);
    $container->expects($this->once())->method('get')->with('config')->willReturn([
        'logger' => ['email_hash_salt' => 'factory-salt-456'],
    ]);

    $factory = new EmailHashSaltProviderFactory();
    $createdProvider = $factory($container);

    expect($createdProvider)->toBeInstanceOf(EmailHashSaltProviderInterface::class)
        ->and($createdProvider->salt())->toBe('factory-salt-456');
});
