<?php declare(strict_types=1);

namespace Tests\Unit\App\Mailing;

use ownHackathon\App\Mailing\Domain\EmailType;

use function expect;
use function test;

test('email type supports factories and serialization', function (): void {
    $email = EmailType::fromString('person@example.org');
    expect((string) $email)->toBe('person@example.org')
        ->and($email->__serialize())->toBe(['string' => 'person@example.org'])
        ->and($email->jsonSerialize())->toBe('person@example.org')
        ->and(fn (): EmailType => new EmailType('not-an-email'))
        ->toThrow(\ownHackathon\App\Mailing\Exception\InvalidArgumentException::class);
});