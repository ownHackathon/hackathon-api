<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Hydrator;

use App\Account\Identity\Domain\AccountActivation;
use App\Account\Identity\Domain\AccountActivationCollection;
use App\Account\Identity\Domain\AccountActivationCollectionInterface;
use App\Account\Identity\Domain\AccountActivationInterface;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Mailing\Domain\EmailType;
use Core\Clock\DateTimeFormat;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidFactoryInterface;

readonly final class AccountActivationHydrator implements AccountActivationHydratorInterface
{
    public function __construct(
        private UuidFactoryInterface $uuid,
        private LoggerInterface $logger,
    ) {
    }

    #[\Override]
    public function hydrate(array $data): AccountActivationInterface
    {
        return new AccountActivation(
            id: $data['id'],
            email: new EmailType($data['email']),
            token: $this->uuid->fromString($data['token']),
            createdAt: new DateTimeImmutable($data['createdAt']),
        );
    }

    #[\Override]
    public function hydrateCollection(array $data): AccountActivationCollectionInterface
    {
        $collection = new AccountActivationCollection();

        foreach ($data as $entity) {
            try {
                $collection[] = $this->hydrate($entity);
            } catch (\Throwable $exception) {
                $this->logger->warning(IdentityLogMessage::ACCOUNT_ACTIVATION_DATA_SKIPPED, [
                    'activationId' => $entity['id'] ?? null,
                    'exception' => $exception,
                ]);
            }
        }

        return $collection;
    }

    #[\Override]
    public function extract(AccountActivationInterface $object): array
    {
        return [
            'id' => $object->id,
            'email' => $object->email->toString(),
            'token' => $object->token->toString(),
            'createdAt' => $object->createdAt->format(DateTimeFormat::DEFAULT->value),
        ];
    }

    #[\Override]
    public function extractCollection(AccountActivationCollectionInterface $collection): array
    {
        $data = [];

        foreach ($collection as $entity) {
            $data[] = $this->extract($entity);
        }

        return $data;
    }
}
