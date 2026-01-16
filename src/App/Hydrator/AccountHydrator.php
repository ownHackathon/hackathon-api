<?php declare(strict_types=1);

namespace App\Hydrator;

use DateTimeImmutable;
use Exception;
use App\Entity\Account\Account;
use App\Entity\Account\AccountCollection;
use Core\Entity\Account\AccountCollectionInterface;
use Core\Entity\Account\AccountInterface;
use Core\Enum\DateTimeFormat;
use Core\Type\Email;
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
            email: new Email($data['email']),
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
            'uuid' => $object->uuid->getHex()->toString(),
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
