<?php declare(strict_types=1);

namespace Tests\Unit\App\Mailing;

use App\Mailing\Api\EmailType;

use function expect;
use function test;

test('email type supports factories and serialization', function (): void {
    $email = EmailType::fromString('person@example.org');
    expect((string) $email)->toBe('person@example.org')
        ->and($email->__serialize())->toBe(['string' => 'person@example.org'])
        ->and($email->jsonSerialize())->toBe('person@example.org')
        ->and(fn (): EmailType => new EmailType('not-an-email'))
        ->toThrow(\App\Mailing\Exception\InvalidArgumentException::class);
});

test('email value object supports all serialization forms and rejects invalid values', function (): void {
    $email = new EmailType('serialize@example.com');

    expect($email->toString())->toBe('serialize@example.com')
        ->and((string) $email)->toBe('serialize@example.com')
        ->and($email->serialize())->toBe('serialize@example.com')
        ->and($email->__serialize())->toBe(['string' => 'serialize@example.com'])
        ->and($email->jsonSerialize())->toBe('serialize@example.com')
        ->and(EmailType::fromString('factory@example.com')->toString())->toBe('factory@example.com')
        ->and(new EmailType($email)->toString())->toBe('serialize@example.com');

    $restored = new EmailType('old@example.com');
    $restored->unserialize('new@example.com');
    expect($restored->toString())->toBe('new@example.com');
    expect(fn() => new EmailType('invalid'))->toThrow(\App\Mailing\Exception\InvalidArgumentException::class);
});
