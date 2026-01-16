<?php declare(strict_types=1);

namespace App\Entity\Account;

use DateTimeImmutable;
use Core\Entity\Account\AccountActivationInterface;
use Core\Trait\CloneReadonlyClassWith;
use Core\Type\Email;
use Core\Utils\Collectible;
use Ramsey\Uuid\UuidInterface;

readonly class AccountActivation implements AccountActivationInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public ?int $id,
        public Email $email,
        public UuidInterface $token,
        public DateTimeImmutable $createdAt,
    ) {
    }
}
