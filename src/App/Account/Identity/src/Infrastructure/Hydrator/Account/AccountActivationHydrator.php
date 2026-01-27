<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Hydrator\Account;

use DateTimeImmutable;
use Exdrals\Identity\Domain\AccountActivation;
use Exdrals\Identity\Domain\AccountActivationCollection;
use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Shared\Domain\Account\AccountActivationCollectionInterface;
use Exdrals\Shared\Domain\Account\AccountActivationInterface;
use Exdrals\Shared\Domain\Enum\DateTimeFormat;
use Exdrals\Shared\Infrastructure\Hydrator\Account\AccountActivationHydratorInterface;
use Ramsey\Uuid\UuidFactoryInterface;

readonly class AccountActivationHydrator implements AccountActivationHydratorInterface
{
    public function __construct(
        private UuidFactoryInterface $uuid,
    ) {
    }

    public function hydrate(array $data): AccountActivationInterface
    {
        return new AccountActivation(
            id: $data['id'],
            email: new EmailType($data['email']),
            token: $this->uuid->fromString($data['token']),
            createdAt: new DateTimeImmutable($data['createdAt']),
        );
    }

    public function hydrateCollection(array $data): AccountActivationCollectionInterface
    {
        $collection = new AccountActivationCollection();

        foreach ($data as $entity) {
            $collection[] = $this->hydrate($entity);
        }

        return $collection;
    }

    public function extract(AccountActivationInterface $object): array
    {
        return [
            'id' => $object->id,
            'email' => $object->email->toString(),
            'token' => $object->token->toString(),
            'createdAt' => $object->createdAt->format(DateTimeFormat::DEFAULT->value),
        ];
    }

    public function extractCollection(AccountActivationCollectionInterface $collection): array
    {
        $data = [];

        foreach ($collection as $entity) {
            $data[] = $this->extract($entity);
        }

        return $data;
    }
}
