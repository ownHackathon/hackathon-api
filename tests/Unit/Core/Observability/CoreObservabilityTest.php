<?php declare(strict_types=1);

namespace Tests\Unit\Core\Observability;

use ownHackathon\Core\Observability\ChannelLoggerFactory;
use ownHackathon\Core\Observability\EmailHasher;
use ownHackathon\Core\Observability\IpMasker;
use ownHackathon\Core\Observability\LoggerFactory;
use ownHackathon\Core\Observability\LogsPruner;
use ownHackathon\Core\Observability\UserAgentSummarizer;
use Psr\Log\LoggerInterface;

use function bin2hex;
use function count;
use function expect;
use function file_get_contents;
use function glob;
use function is_dir;
use function json_decode;
use function mkdir;
use function random_bytes;
use function rmdir;
use function sys_get_temp_dir;
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

test('logger factory writes only the configured single file for a channel', function (): void {
    $path = sys_get_temp_dir() . '/hackathon-unit-logger-' . bin2hex(random_bytes(4));
    mkdir($path);
    $container = $this->createMock(\Psr\Container\ContainerInterface::class);
    $container->method('get')->with('config')->willReturn([
        'logger' => [
            'path' => $path,
            'channels' => [
                'account-activity' => ['file' => 'account-activity.log', 'format' => 'line'],
            ],
        ],
    ]);

    (new ChannelLoggerFactory())($container, 'logger.account-activity')->info('activity');

    $dateDir = $path . '/' . date('Y-m-d');
    expect(file_get_contents($dateDir . '/account-activity.log'))->toContain('activity');

    $files = glob($dateDir . '/*');
    expect(count($files))->toBe(1);
});

test('ip masker masks ipv4 last octet and ipv6 last group', function (): void {
    expect(IpMasker::mask('192.168.1.25'))->toBe('192.168.1.x')
        ->and(IpMasker::mask('2001:db8::1'))->toBe('2001:db8::x')
        ->and(IpMasker::mask(''))->toBe(IpMasker::UNKNOWN)
        ->and(IpMasker::mask(null))->toBe(IpMasker::UNKNOWN);
});

test('user agent summarizer reduces user agents to a coarse type', function (): void {
    expect(UserAgentSummarizer::summarize('curl/8.0.0 x86_64'))->toBe('curl')
        ->and(UserAgentSummarizer::summarize('PostmanRuntime/7.0.0'))->toBe('postman')
        ->and(UserAgentSummarizer::summarize('Mozilla/5.0 (Mobile)'))->toBe('browser-mobile')
        ->and(UserAgentSummarizer::summarize('Mozilla/5.0 (X11; Linux) Chrome/120'))->toBe('browser')
        ->and(UserAgentSummarizer::summarize(''))->toBe(UserAgentSummarizer::UNKNOWN)
        ->and(UserAgentSummarizer::summarize(null))->toBe(UserAgentSummarizer::UNKNOWN);
});

test('email hasher produces a stable salted hash without plaintext', function (): void {
    $hash = EmailHasher::hash(' User@Example.com ', 'salt');
    expect($hash)->toBe(EmailHasher::hash('user@example.com', 'salt'))
        ->and($hash)->not->toContain('User@Example.com')
        ->and($hash)->toHaveLength(64);
});

test('logs pruner removes only outdated date directories', function (): void {
    $path = sys_get_temp_dir() . '/hackathon-unit-logs-' . bin2hex(random_bytes(4));
    mkdir($path);

    $old = $path . '/' . date('Y-m-d', strtotime('-40 days'));
    $fresh = $path . '/' . date('Y-m-d', strtotime('-2 days'));
    $notDate = $path . '/not-a-date';

    mkdir($old);
    mkdir($fresh);
    mkdir($notDate);

    $removed = LogsPruner::prune($path, 30);

    expect($removed)->toBe(1)
        ->and(is_dir($old))->toBeFalse()
        ->and(is_dir($fresh))->toBeTrue()
        ->and(is_dir($notDate))->toBeTrue();

    rmdir($fresh);
    rmdir($notDate);
});
