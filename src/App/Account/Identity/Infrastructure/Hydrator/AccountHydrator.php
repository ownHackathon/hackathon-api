<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Hydrator;

use DateTimeImmutable;
use Exception;
use ownHackathon\App\Account\Identity\Domain\Account;
use ownHackathon\App\Account\Identity\Domain\AccountCollection;
use ownHackathon\App\Account\Identity\Domain\AccountCollectionInterface;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\Core\Clock\DateTimeFormat;
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
            lastActionAt: $data['lastActionAt'] ? new DateTimeImmutable($data['lastActionAt']) : null,
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
            'lastActionAt' => $object->lastActionAt?->format(DateTimeFormat::DEFAULT->value),
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
