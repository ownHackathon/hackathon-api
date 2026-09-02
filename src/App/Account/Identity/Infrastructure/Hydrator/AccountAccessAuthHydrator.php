<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Hydrator;

use DateTimeImmutable;
use Exception;
use ownHackathon\App\Account\Identity\Domain\AccountAccessAuth;
use ownHackathon\App\Account\Identity\Domain\AccountAccessAuthCollection;
use ownHackathon\App\Account\Identity\Domain\AccountAccessAuthCollectionInterface;
use ownHackathon\App\Account\Identity\Domain\AccountAccessAuthInterface;
use ownHackathon\Core\Clock\DateTimeFormat;

readonly final class AccountAccessAuthHydrator implements AccountAccessAuthHydratorInterface
{
    /**
     * @throws Exception
     */
    #[\Override]
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
    #[\Override]
    public function hydrateCollection(array $data): AccountAccessAuthCollection
    {
        $collection = new AccountAccessAuthCollection();

        foreach ($data as $entity) {
            $collection[] = $this->hydrate($entity);
        }

        return $collection;
    }

    #[\Override]
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

    #[\Override]
    public function extractCollection(AccountAccessAuthCollectionInterface $collection): array
    {
        $data = [];

        foreach ($collection as $entity) {
            $data[] = $this->extract($entity);
        }

        return $data;
    }
}
