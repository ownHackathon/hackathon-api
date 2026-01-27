<?php declare(strict_types=1);

namespace UnitTest\Mock\Entity;

use DateTimeImmutable;
use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Shared\Domain\Account\AccountInterface;
use Ramsey\Uuid\Nonstandard\Uuid;
use Ramsey\Uuid\UuidInterface;
use UnitTest\Mock\Constants\Account;

class MockAccount implements AccountInterface
{
    public int|null $id {
        get {
            return Account::ID;
        }
    }

    public UuidInterface $uuid {
        get {
            return Uuid::fromString(Account::UUID);
        }
    }

    public string $name {
        get {
            return '';
        }
    }
    public string $password {
        get {
            return '';
        }
    }
    public EmailType $email {
        get {
            return new EmailType(Account::EMAIL);
        }
    }
    public DateTimeImmutable $registeredAt {
        get {
            return new DateTimeImmutable();
        }
    }
    public DateTimeImmutable $lastActionAt {
        get {
            return new DateTimeImmutable();
        }
    }

    public function with(...$properties): AccountInterface
    {
        // TODO: Implement with() method.
    }
}
