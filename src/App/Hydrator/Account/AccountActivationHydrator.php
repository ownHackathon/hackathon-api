<?php declare(strict_types=1);

namespace App\Hydrator\Account;

use App\Entity\Account\AccountActivation;
use App\Entity\Account\AccountActivationCollection;
use Core\Entity\Account\AccountActivationCollectionInterface;
use Core\Entity\Account\AccountActivationInterface;
use Core\Enum\DateTimeFormat;
use Core\Type\Email;
use DateTimeImmutable;
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
            email: new Email($data['email']),
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
            'token' => $object->token->getHex(),
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
