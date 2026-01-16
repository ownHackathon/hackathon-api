<?php declare(strict_types=1);

namespace ownHackathon\App\Hydrator;

use DateTimeImmutable;
use Exception;
use Ramsey\Uuid\UuidFactoryInterface;
use ownHackathon\App\Entity\Account;
use ownHackathon\App\Entity\AccountCollection;
use ownHackathon\Core\Entity\Account\AccountCollectionInterface;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Enum\DateTimeFormat;
use ownHackathon\Core\Type\Email;

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
            'id' => $object->getId(),
            'uuid' => $object->getUuid()->getHex()->toString(),
            'name' => $object->getName(),
            'password' => $object->getPasswordHash(),
            'email' => $object->getEMail()->toString(),
            'registeredAt' => $object->getRegisteredAt()->format(DateTimeFormat::DEFAULT->value),
            'lastActionAt' => $object->getLastActionAt()->format(DateTimeFormat::DEFAULT->value),
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
