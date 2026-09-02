<?php declare(strict_types=1);

namespace Tests\Unit\Composition;

use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Event\Domain\Message\EventLogMessage;
use ownHackathon\App\Token\Domain\Message\TokenLogMessage;
use ownHackathon\App\Workspace\Domain\Message\WorkspaceLogMessage;
use ownHackathon\Core\SharedKernel\Domain\Message\LogMessage;

use function expect;
use function test;

test('all log message interfaces expose their shared base constant', function (): void {
    $interfaces = [
        LogMessage::class,
        IdentityLogMessage::class,
        EventLogMessage::class,
        TokenLogMessage::class,
        WorkspaceLogMessage::class,
    ];

    foreach ($interfaces as $interface) {
        expect(defined($interface . '::UNAUTHORIZED_ACCESS'))->toBeTrue()
            ->and(constant($interface . '::UNAUTHORIZED_ACCESS'))
            ->toBe(LogMessage::UNAUTHORIZED_ACCESS);
    }
});

test('event log message constants are unique and non-empty', function (): void {
    expect(EventLogMessage::INVALID_EVENT_VISIBILITY)->not->toBe('')
        ->and(EventLogMessage::EVENT_DATA_SKIPPED)->not->toBe('')
        ->and(EventLogMessage::INVALID_EVENT_VISIBILITY)->not->toBe(EventLogMessage::EVENT_DATA_SKIPPED);
});

test('workspace log message constants cover validator and hydrator cases', function (): void {
    $constants = [
        WorkspaceLogMessage::INVALID_WORKSPACE_NAME,
        WorkspaceLogMessage::DUPLICATED_WORKSPACE_NAME,
        WorkspaceLogMessage::INVALID_WORKSPACE_VISIBILITY,
        WorkspaceLogMessage::WORKSPACE_DATA_SKIPPED,
    ];

    expect($constants)->each->not->toBe('')
        ->and(count($constants))->toBe(count(array_unique($constants)));
});

test('identity log message constants cover account hydrator cases', function (): void {
    expect(IdentityLogMessage::ACCOUNT_DATA_SKIPPED)->not->toBe('')
        ->and(IdentityLogMessage::ACCOUNT_ACTIVATION_DATA_SKIPPED)->not->toBe('')
        ->and(IdentityLogMessage::ACCOUNT_DATA_SKIPPED)->not->toBe(IdentityLogMessage::ACCOUNT_ACTIVATION_DATA_SKIPPED);
});

test('token log message constants are unique and non-empty', function (): void {
    expect(TokenLogMessage::TOKEN_DATA_SKIPPED)->not->toBe('');
});
