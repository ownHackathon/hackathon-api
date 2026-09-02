<?php declare(strict_types=1);

namespace Tests\Unit\Core\Observability;

use ownHackathon\Core\Observability\ChannelLoggerFactory;
use ownHackathon\Core\Observability\LoggerFactory;
use Psr\Log\LoggerInterface;

use function expect;
use function file_get_contents;
use function json_decode;
use function test;

test('logger factory creates the default channel and dated log directory', function (): void {
    $path = sys_get_temp_dir() . '/hackathon-unit-logger-' . bin2hex(random_bytes(4));
    mkdir($path);
    $container = $this->createMock(\Psr\Container\ContainerInterface::class);
    $container->method('get')->with('config')->willReturn(['logger' => ['path' => $path]]);

    $logger = (new LoggerFactory())($container);

    expect($logger)->toBeInstanceOf(LoggerInterface::class)
        ->and($logger)->toBeInstanceOf(\Monolog\Logger::class)
        ->and($logger->getName())->toBe(LoggerFactory::DEFAULT_CHANNEL)
        ->and(is_dir($path . '/' . date('Y-m-d')))->toBeTrue();
});

test('channel logger factory builds a logger with the requested channel name', function (): void {
    $path = sys_get_temp_dir() . '/hackathon-unit-logger-' . bin2hex(random_bytes(4));
    mkdir($path);
    $container = $this->createMock(\Psr\Container\ContainerInterface::class);
    $container->method('get')->with('config')->willReturn(['logger' => ['path' => $path]]);

    $logger = (new ChannelLoggerFactory())($container, 'logger.identity');

    expect($logger)->toBeInstanceOf(LoggerInterface::class)
        ->and($logger)->toBeInstanceOf(\Monolog\Logger::class)
        ->and($logger->getName())->toBe('identity');
});

test('channel logger factory falls back to the default channel for unknown names', function (): void {
    $path = sys_get_temp_dir() . '/hackathon-unit-logger-' . bin2hex(random_bytes(4));
    mkdir($path);
    $container = $this->createMock(\Psr\Container\ContainerInterface::class);
    $container->method('get')->with('config')->willReturn(['logger' => ['path' => $path]]);

    $logger = (new ChannelLoggerFactory())($container, 'some-other-service');

    expect($logger)->toBeInstanceOf(LoggerInterface::class)
        ->and($logger)->toBeInstanceOf(\Monolog\Logger::class)
        ->and($logger->getName())->toBe(LoggerFactory::DEFAULT_CHANNEL);
});

test('logger factory writes line formatted output by default', function (): void {
    $path = sys_get_temp_dir() . '/hackathon-unit-logger-' . bin2hex(random_bytes(4));
    mkdir($path);
    $container = $this->createMock(\Psr\Container\ContainerInterface::class);
    $container->method('get')->with('config')->willReturn(['logger' => ['path' => $path]]);

    (new LoggerFactory())($container)->info('hello world');

    $line = file_get_contents($path . '/' . date('Y-m-d') . '/default.log');
    expect($line)->toContain('INFO')
        ->and($line)->toContain('hello world')
        ->and($line)->not->toStartWith('{');
});

test('logger factory writes json formatted output when configured', function (): void {
    $path = sys_get_temp_dir() . '/hackathon-unit-logger-' . bin2hex(random_bytes(4));
    mkdir($path);
    $container = $this->createMock(\Psr\Container\ContainerInterface::class);
    $container->method('get')->with('config')->willReturn([
        'logger' => ['path' => $path, 'format' => LoggerFactory::FORMAT_JSON],
    ]);

    (new LoggerFactory())($container)->info('hello world');

    $json = json_decode(
        trim((string) file_get_contents($path . '/' . date('Y-m-d') . '/default.log')),
        true,
        512,
        JSON_THROW_ON_ERROR,
    );
    expect($json)->toHaveKey('message')->and($json['message'])->toBe('hello world')
        ->and($json)->toHaveKey('level_name')->and($json['level_name'])->toBe('INFO');
});
