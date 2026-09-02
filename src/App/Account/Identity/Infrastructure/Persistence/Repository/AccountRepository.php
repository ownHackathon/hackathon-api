<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Persistence\Repository;

use ownHackathon\App\Account\Identity\Domain\AccountCollectionInterface;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountHydratorInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Table\AccountStoreInterface;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\Core\Persistence\Hydrator\HydratorInterface;
use ownHackathon\Core\Persistence\Repository\AbstractRepository;
use ownHackathon\Core\Persistence\Store\StoreInterface;
use Ramsey\Uuid\UuidInterface;

readonly final class AccountRepository extends AbstractRepository implements AccountRepositoryInterface
{
    public function __construct(
        private AccountStoreInterface $store,
        private AccountHydratorInterface $hydrator,
    ) {
    }

    #[\Override]
    public function insert(AccountInterface $data): int
    {
        $data = $this->hydrator->extract($data);

        return $this->store->persist($data);
    }

    #[\Override]
    public function update(AccountInterface $data): true
    {
        $data = $this->hydrator->extract($data);

        return $this->store->update($data['id'], $data);
    }

    #[\Override]
    public function deleteById(int $id): true
    {
        return $this->store->remove(['id' => $id]);
    }

    #[\Override]
    public function findOneById(int $id): ?AccountInterface
    {
        $result = $this->store->fetchOne(['id' => $id]);

        return $this->mapToEntity($result);
    }

    #[\Override]
    public function findOneByUuid(UuidInterface $uuid): ?AccountInterface
    {
        $result = $this->store->fetchOne(['uuid' => $uuid]);

        return $this->mapToEntity($result);
    }

    #[\Override]
    public function findOneByName(string $name): ?AccountInterface
    {
        $result = $this->store->fetchOne(['name' => $name]);

        return $this->mapToEntity($result);
    }

    #[\Override]
    public function findOneByEmail(EmailType $email): ?AccountInterface
    {
        $result = $this->store->fetchOne(['email' => $email]);

        return $this->mapToEntity($result);
    }

    #[\Override]
    public function findAll(): AccountCollectionInterface
    {
        $result = $this->store->fetchAll();

        return $this->mapToCollection($result);
    }

    #[\Override]
    protected function getHydrator(): HydratorInterface
    {
        return $this->hydrator;
    }

    #[\Override]
    protected function getStore(): StoreInterface
    {
        return $this->store;
    }
}
