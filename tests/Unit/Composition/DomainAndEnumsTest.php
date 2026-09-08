<?php declare(strict_types=1);

namespace Tests\Unit\Composition;

use App\Account\Identity\Domain\Enum\AccountRoles;
use App\Account\Identity\Domain\Enum\AccountVisibleStatus;
use App\Token\Api\Enum\TokenType;

use function expect;
use function test;

test('enums expose names', function (): void {
    expect(AccountRoles::Owner->getAccountRoleName())->toBe('Owner')
        ->and(AccountVisibleStatus::DO_NOT_DISTURB->getVisibleStatusName())->toBe('do not-disturb')
        ->and(TokenType::EMail->value)->toBe(2);
});
