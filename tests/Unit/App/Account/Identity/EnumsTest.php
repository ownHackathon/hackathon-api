<?php declare(strict_types=1);

namespace Tests\Unit\App\Account\Identity;

use App\Account\Identity\Api\Enum\AccountRoles;
use App\Account\Identity\Api\Enum\AccountVisibleStatus;

use function expect;
use function test;

test('all account enum cases expose a non-empty display name', function (): void {
    foreach (AccountRoles::cases() as $role) {
        expect($role->getAccountRoleName())->not->toBe('');
    }
    foreach (AccountVisibleStatus::cases() as $status) {
        expect($status->getVisibleStatusName())->not->toBe('');
    }
});
