<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Hydrator;

use DateTimeImmutable;
use ownHackathon\App\Account\Identity\Domain\AccountActivation;
use ownHackathon\App\Account\Identity\Domain\AccountActivationCollection;
use ownHackathon\App\Account\Identity\Domain\AccountActivationCollectionInterface;
use ownHackathon\App\Account\Identity\Domain\AccountActivationInterface;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\Core\Clock\DateTimeFormat;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidFactoryInterface;

readonly final class AccountActivationHydrator implements AccountActivationHydratorInterface
{
    public function __construct(
        private UuidFactoryInterface $uuid,
        private ?LoggerInterface $logger = null,
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
            try {
                $collection[] = $this->hydrate($entity);
            } catch (\Throwable $exception) {
                $this->logger?->warning('Invalid account activation persistence data skipped.', [
                    'activationId' => $entity['id'] ?? null,
                    'exception' => $exception,
                ]);
            }
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
