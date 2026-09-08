<?php declare(strict_types=1);

namespace Tests\Unit\App\Account\Identity;

use App\Account\Identity\Domain\Message\IdentityLogMessage;

use function array_unique;
use function count;
use function expect;
use function test;

test('identity log message constants cover account hydrator cases', function (): void {
    expect(IdentityLogMessage::ACCOUNT_DATA_SKIPPED)->not->toBe('')
        ->and(IdentityLogMessage::ACCOUNT_ACTIVATION_DATA_SKIPPED)->not->toBe('')
        ->and(IdentityLogMessage::ACCOUNT_DATA_SKIPPED)->not->toBe(IdentityLogMessage::ACCOUNT_ACTIVATION_DATA_SKIPPED);
});

test('identity activity log message constants are unique and non-empty', function (): void {
    $constants = [
        IdentityLogMessage::ACTIVITY_INTERACTION,
        IdentityLogMessage::ACTIVITY_INTERACTION_WARNING,
        IdentityLogMessage::ACTIVITY_INTERACTION_ERROR,
        IdentityLogMessage::ACTIVITY_LOGIN_SUCCESS,
        IdentityLogMessage::ACTIVITY_LOGIN_FAILED,
        IdentityLogMessage::ACTIVITY_REGISTER_REQUESTED,
        IdentityLogMessage::ACTIVITY_PASSWORD_CHANGE_REQUESTED,
        IdentityLogMessage::ACTIVITY_PASSWORD_CHANGED,
        IdentityLogMessage::ACTIVITY_LOGOUT,
        IdentityLogMessage::ACTIVITY_TOKEN_REFRESHED,
        IdentityLogMessage::ACTIVITY_ACCOUNT_ACTIVATED,
        IdentityLogMessage::ACTIVITY_SUSPICIOUS_ACCESS,
    ];

    expect($constants)->each->not->toBe('')
        ->and(count($constants))->toBe(count(array_unique($constants)));
});
