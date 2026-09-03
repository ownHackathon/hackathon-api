<?php declare(strict_types=1);

namespace Tests\Unit\App\Event;

use App\Event\Application\Port\EventLoggerInterface;
use App\Event\Infrastructure\Factory\EventLoggerFactory;
use App\Event\Infrastructure\Logger\EventLogger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function expect;
use function sys_get_temp_dir;
use function test;

test('event logger factory creates EventLoggerInterface instance for event channel', function (): void {
    $path = sys_get_temp_dir() . '/test-event-logger-' . bin2hex(random_bytes(4));
    mkdir($path);

    $container = $this->createMock(ContainerInterface::class);
    $container->expects($this->once())->method('get')->with('config')->willReturn([
        'logger' => ['path' => $path],
    ]);

    $factory = new EventLoggerFactory();
    $logger = $factory($container);

    expect($logger)->toBeInstanceOf(EventLoggerInterface::class)
        ->and($logger)->toBeInstanceOf(EventLogger::class);
});

test('event logger delegates calls to inner logger', function (): void {
    $innerLogger = $this->createMock(LoggerInterface::class);
    $innerLogger->expects($this->once())->method('log')->with('error', 'event error message', []);

    $eventLogger = new EventLogger($innerLogger);
    $eventLogger->error('event error message');
});
