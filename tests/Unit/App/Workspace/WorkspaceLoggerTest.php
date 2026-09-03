<?php declare(strict_types=1);

namespace Tests\Unit\App\Workspace;

use App\Workspace\Application\Port\WorkspaceLoggerInterface;
use App\Workspace\Infrastructure\Factory\WorkspaceLoggerFactory;
use App\Workspace\Infrastructure\Logger\WorkspaceLogger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function expect;
use function sys_get_temp_dir;
use function test;

test('workspace logger factory creates WorkspaceLoggerInterface instance for workspace channel', function (): void {
    $path = sys_get_temp_dir() . '/test-workspace-logger-' . bin2hex(random_bytes(4));
    mkdir($path);

    $container = $this->createMock(ContainerInterface::class);
    $container->expects($this->once())->method('get')->with('config')->willReturn([
        'logger' => ['path' => $path],
    ]);

    $factory = new WorkspaceLoggerFactory();
    $logger = $factory($container);

    expect($logger)->toBeInstanceOf(WorkspaceLoggerInterface::class)
        ->and($logger)->toBeInstanceOf(WorkspaceLogger::class);
});

test('workspace logger delegates calls to inner logger', function (): void {
    $innerLogger = $this->createMock(LoggerInterface::class);
    $innerLogger->expects($this->once())->method('log')->with('notice', 'workspace notice message', []);

    $workspaceLogger = new WorkspaceLogger($innerLogger);
    $workspaceLogger->notice('workspace notice message');
});
