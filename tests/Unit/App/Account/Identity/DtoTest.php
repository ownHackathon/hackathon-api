<?php declare(strict_types=1);

namespace Tests\Unit\App\Account\Identity;

use App\Account\Identity\Api\DTO\Account\Account as AccountDto;
use App\Account\Identity\Api\DTO\Account\AccountRegistration;
use App\Account\Identity\Api\DTO\Client\ClientIdentificationData;
use App\Mailing\Api\EmailType;
use Core\Clock\DateTimeFormat;
use Core\SharedKernel\Domain\Exception\DuplicateEntryException;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

const ACCOUNT_TEST_UUID = '019becbe-f952-7b82-82fa-f41f8ae24599';

test('identity DTO factories map input values', function (): void {
    expect((array) AccountRegistration::fromString('Alice', 'secret'))
        ->toBe(['accountName' => 'Alice', 'password' => 'secret'])
        ->and(ClientIdentificationData::create(null, 'agent')->ident)->toBe('unsecure');
});

test('account DTO formats domain dates', function (): void {
    $account = new \App\Account\Identity\Domain\Account(
        id: 1,
        uuid: Uuid::fromString(ACCOUNT_TEST_UUID),
        name: 'Alice',
        hashedPassword: 'hash',
        email: new EmailType('alice@example.com'),
        registeredAt: new DateTimeImmutable('2024-01-02 03:04:05'),
        lastActionAt: new DateTimeImmutable('2024-01-03 04:05:06'),
    );
    $dto = AccountDTO::fromArray([
        'uuid' => $account->uuid->toString(),
        'name' => $account->name,
        'email' => $account->email->toString(),
        'registeredAt' => $account->registeredAt->format(DateTimeFormat::DEFAULT->value),
        'lastActionAt' => $account->lastActionAt->format(DateTimeFormat::DEFAULT->value),
    ]);
    expect($dto->registeredAt)->toBe('2024-01-02 03:04:05')->and($dto->email)->toBe('alice@example.com');
});

test('identity domain exceptions retain their diagnostic values', function (): void {
    $exception = new \App\Account\Identity\Domain\Exception\SecurityBreachException('expected', 'actual', 'browser', 'other');
    expect($exception->expectedClientHash)->toBe('expected')
        ->and($exception->actualUserAgent)->toBe('other');

    $duplicate = new DuplicateEntryException('Account', ['email' => 'a@example.org']);
    expect($duplicate->getCode())->toBe(400)->and($duplicate->getMessage())->toContain('Account');
});
