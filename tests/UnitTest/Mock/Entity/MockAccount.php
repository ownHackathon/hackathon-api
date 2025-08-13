<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Type\Email;
use ownHackathon\UnitTest\Mock\Constants\Account;

class MockAccount implements AccountInterface
{
    public function getId(): int
    {
        // TODO: Implement getId() method.
    }

    public function getUuid(): UuidInterface
    {
        return Uuid::fromString(Account::UUID);
    }

    public function getName(): string
    {
        // TODO: Implement getName() method.
    }

    public function getPasswordHash(): string
    {
        // TODO: Implement getPasswordHash() method.
    }

    public function getEMail(): Email
    {
        // TODO: Implement getEMail() method.
    }

    public function getRegisteredAt(): DateTimeImmutable
    {
        // TODO: Implement getRegisteredAt() method.
    }

    public function getLastActionAt(): DateTimeImmutable
    {
        // TODO: Implement getLastActionAt() method.
    }
}
