<?php declare(strict_types=1);

namespace Tests\Unit\Composition;

use App\Account\Identity\Domain\Enum\AccountRoles;
use App\Account\Identity\Domain\Enum\AccountVisibleStatus;
use App\Event\Domain\Enum\EventStatus;
use App\Policy\Domain\Enum\Visibility;
use App\Token\Domain\Enum\TokenType;

use function expect;
use function test;

test('enums expose names and visibility rules', function (): void {
    expect(AccountRoles::Owner->getAccountRoleName())->toBe('Owner')
        ->and(AccountVisibleStatus::DO_NOT_DISTURB->getVisibleStatusName())->toBe('do not-disturb')
        ->and(EventStatus::RUNNING->getEventStatusName())->toBe('Running')
        ->and(Visibility::PUBLIC->getVisibilityName())->toBe('Public')
        ->and(Visibility::REGISTERED->getVisibilityName())->toBe('Registered User')
        ->and(Visibility::UNLISTED->getVisibilityName())->toBe('Unlisted')
        ->and(TokenType::EMail->value)->toBe(2);
});

test('all enum cases expose a non-empty display name', function (): void {
    foreach (AccountRoles::cases() as $role) {
        expect($role->getAccountRoleName())->not->toBe('');
    }
    foreach (AccountVisibleStatus::cases() as $status) {
        expect($status->getVisibleStatusName())->not->toBe('');
    }
    foreach (EventStatus::cases() as $status) {
        expect($status->getEventStatusName())->not->toBe('');
    }
    foreach (Visibility::cases() as $visibility) {
        expect($visibility->getVisibilityName())->not->toBe('');
    }
});
