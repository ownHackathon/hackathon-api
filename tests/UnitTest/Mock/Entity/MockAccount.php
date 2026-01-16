<?php declare(strict_types=1);

namespace UnitTest\Mock\Entity;

use DateTimeImmutable;
use Core\Entity\Account\AccountInterface;
use Core\Type\Email;
use UnitTest\Mock\Constants\Account;
use Ramsey\Uuid\Nonstandard\Uuid;
use Ramsey\Uuid\UuidInterface;

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
    public Email $email {
        get {
            return new Email(Account::EMAIL);
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
