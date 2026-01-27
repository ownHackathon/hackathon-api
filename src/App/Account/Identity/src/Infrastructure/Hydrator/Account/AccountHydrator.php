<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Hydrator\Account;

use DateTimeImmutable;
use Exception;
use Exdrals\Identity\Domain\Account;
use Exdrals\Identity\Domain\AccountCollection;
use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Shared\Domain\Account\AccountCollectionInterface;
use Exdrals\Shared\Domain\Account\AccountInterface;
use Exdrals\Shared\Domain\Enum\DateTimeFormat;
use Exdrals\Shared\Infrastructure\Hydrator\Account\AccountHydratorInterface;
use Ramsey\Uuid\UuidFactoryInterface;

readonly class AccountHydrator implements AccountHydratorInterface
{
    public function __construct(
        private UuidFactoryInterface $uuid,
    ) {
    }

    /**
     * @throws Exception
     */
    public function hydrate(array $data): AccountInterface
    {
        return new Account(
            id: $data['id'],
            uuid: $this->uuid->fromString($data['uuid']),
            name: $data['name'],
            password: $data['password'],
            email: new EmailType($data['email']),
            registeredAt: new DateTimeImmutable($data['registeredAt']),
            lastActionAt: new DateTimeImmutable($data['lastActionAt']),
        );
    }

    /**
     * @throws Exception
     */
    public function hydrateCollection(array $data): AccountCollectionInterface
    {
        $collection = new AccountCollection();

        foreach ($data as $entity) {
            $collection[] = $this->hydrate($entity);
        }

        return $collection;
    }

    public function extract(AccountInterface $object): array
    {
        return [
            'id' => $object->id,
            'uuid' => $object->uuid->toString(),
            'name' => $object->name,
            'password' => $object->password,
            'email' => $object->email->toString(),
            'registeredAt' => $object->registeredAt->format(DateTimeFormat::DEFAULT->value),
            'lastActionAt' => $object->lastActionAt->format(DateTimeFormat::DEFAULT->value),
        ];
    }

    public function extractCollection(AccountCollectionInterface $collection): array
    {
        $data = [];

        foreach ($collection as $entity) {
            $data[] = $this->extract($entity);
        }

        return $data;
    }
}
