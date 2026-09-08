<?php declare(strict_types=1);

namespace Tests\Unit\Composition;

use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Token\Domain\Message\TokenLogMessage;
use Core\SharedKernel\Domain\Message\LogMessage;

use function expect;
use function test;

test('all log message interfaces expose their shared base constant', function (): void {
    $interfaces = [
        LogMessage::class,
        IdentityLogMessage::class,
        TokenLogMessage::class,
    ];

    foreach ($interfaces as $interface) {
        expect(defined($interface . '::UNAUTHORIZED_ACCESS'))->toBeTrue()
            ->and(constant($interface . '::UNAUTHORIZED_ACCESS'))
            ->toBe(LogMessage::UNAUTHORIZED_ACCESS);
    }
});
