<?php declare(strict_types=1);

namespace Tests\Unit\App\Token;

use App\Token\Application\Port\TokenLoggerInterface;
use App\Token\Infrastructure\Factory\TokenLoggerFactory;
use App\Token\Infrastructure\Logger\TokenLogger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function expect;
use function sys_get_temp_dir;
use function test;

test('token logger factory creates TokenLoggerInterface instance for token channel', function (): void {
    $path = sys_get_temp_dir() . '/test-token-logger-' . bin2hex(random_bytes(4));
    mkdir($path);

    $container = $this->createMock(ContainerInterface::class);
    $container->expects($this->once())->method('get')->with('config')->willReturn([
        'logger' => ['path' => $path],
    ]);

    $factory = new TokenLoggerFactory();
    $logger = $factory($container);

    expect($logger)->toBeInstanceOf(TokenLoggerInterface::class)
        ->and($logger)->toBeInstanceOf(TokenLogger::class);
});

test('token logger delegates calls to inner logger', function (): void {
    $innerLogger = $this->createMock(LoggerInterface::class);
    $innerLogger->expects($this->once())->method('log')->with('debug', 'token debug message', []);

    $tokenLogger = new TokenLogger($innerLogger);
    $tokenLogger->debug('token debug message');
});
