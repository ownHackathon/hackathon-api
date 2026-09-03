<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Hydrator;

use App\Account\Identity\Application\Port\IdentityLoggerInterface;
use App\Account\Identity\Domain\Account;
use App\Account\Identity\Domain\AccountCollection;
use App\Account\Identity\Domain\AccountCollectionInterface;
use App\Account\Identity\Domain\AccountInterface;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Mailing\Domain\EmailType;
use Core\Clock\DateTimeFormat;
use DateTimeImmutable;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Ramsey\Uuid\UuidFactoryInterface;

readonly final class AccountHydrator implements AccountHydratorInterface
{
    public function __construct(
        private UuidFactoryInterface $uuid,
        private IdentityLoggerInterface $logger,
    ) {
    }

    /**
     * @throws Exception
     */
    #[\Override]
    #[ArrayShape([
        'id' => 'int|null',
        'uuid' => 'string',
        'name' => 'string',
        'password' => 'string',
        'email' => 'string',
        'registeredAt' => 'string',
        'lastActionAt' => 'string|null',
    ])]
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
    #[\Override]
    public function hydrateCollection(array $data): AccountCollectionInterface
    {
        $collection = new AccountCollection();

        foreach ($data as $entity) {
            try {
                $collection[] = $this->hydrate($entity);
            } catch (\Throwable $exception) {
                $this->logger->warning(IdentityLogMessage::ACCOUNT_DATA_SKIPPED, [
                    'accountId' => $entity['id'] ?? null,
                    'exception' => $exception,
                ]);
            }
        }

        return $collection;
    }

    #[\Override]
    #[ArrayShape([
        'id' => 'int|null',
        'uuid' => 'string',
        'name' => 'string',
        'password' => 'string',
        'email' => 'string',
        'registeredAt' => 'string',
        'lastActionAt' => 'string|null',
    ])]
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

    #[\Override]
    public function extractCollection(AccountCollectionInterface $collection): array
    {
        $data = [];

        foreach ($collection as $entity) {
            $data[] = $this->extract($entity);
        }

        return $data;
    }
}
