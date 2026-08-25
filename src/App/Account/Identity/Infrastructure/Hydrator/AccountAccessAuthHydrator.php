<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Hydrator;

use DateTimeImmutable;
use Exception;
use Exdrals\Core\Shared\Domain\Enum\DateTimeFormat;
use Exdrals\Identity\Domain\AccountAccessAuth;
use Exdrals\Identity\Domain\AccountAccessAuthCollection;
use Exdrals\Identity\Domain\AccountAccessAuthCollectionInterface;
use Exdrals\Identity\Domain\AccountAccessAuthInterface;

readonly class AccountAccessAuthHydrator implements AccountAccessAuthHydratorInterface
{
    /**
     * @throws Exception
     */
    public function hydrate(array $data): AccountAccessAuthInterface
    {
        return new AccountAccessAuth(
            id: $data['id'],
            accountId: $data['accountId'],
            label: $data['label'],
            refreshToken: $data['refreshToken'],
            userAgent: $data['userAgent'],
            clientIdentHash: $data['clientIdentHash'],
            createdAt: new DateTimeImmutable($data['createdAt']),
        );
    }

    /**
     * @throws Exception
     */
    public function hydrateCollection(array $data): AccountAccessAuthCollection
    {
        $collection = new AccountAccessAuthCollection();

        foreach ($data as $entity) {
            $collection[] = $this->hydrate($entity);
        }

        return $collection;
    }

    public function extract(AccountAccessAuthInterface $object): array
    {
        return [
            'id' => $object->id,
            'accountId' => $object->accountId,
            'label' => $object->label,
            'refreshToken' => $object->refreshToken,
            'userAgent' => $object->userAgent,
            'clientIdentHash' => $object->clientIdentHash,
            'createdAt' => $object->createdAt->format(DateTimeFormat::DEFAULT->value),
        ];
    }

    public function extractCollection(AccountAccessAuthCollectionInterface $collection): array
    {
        $data = [];

        foreach ($collection as $entity) {
            $data[] = $this->extract($entity);
        }

        return $data;
    }
}
