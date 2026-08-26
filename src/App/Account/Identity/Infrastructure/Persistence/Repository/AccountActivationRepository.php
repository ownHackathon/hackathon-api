<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Persistence\Repository;

use ownHackathon\Core\Mailing\Domain\EmailType;
use ownHackathon\Core\Shared\Infrastructure\Hydrator\HydratorInterface;
use ownHackathon\Core\Shared\Infrastructure\Persistence\Repository\AbstractRepository;
use ownHackathon\Core\Shared\Infrastructure\Persistence\Store\StoreInterface;
use ownHackathon\App\Account\Identity\Domain\AccountActivationCollectionInterface;
use ownHackathon\App\Account\Identity\Domain\AccountActivationInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountActivationHydratorInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Table\AccountActivationStoreInterface;

readonly class AccountActivationRepository extends AbstractRepository implements AccountActivationRepositoryInterface
{
    public function __construct(
        private AccountActivationStoreInterface $store,
        private AccountActivationHydratorInterface $hydrator,
    ) {
    }

    protected function getHydrator(): HydratorInterface
    {
        return $this->hydrator;
    }

    protected function getStore(): StoreInterface
    {
        return $this->store;
    }

    public function insert(AccountActivationInterface $data): int
    {
        $data = $this->hydrator->extract($data);

        return $this->store->persist($data);
    }

    public function update(AccountActivationInterface $data): true
    {
        $data = $this->hydrator->extract($data);

        return $this->store->update($data['id'], $data);
    }

    public function findOneById(int $id): ?AccountActivationInterface
    {
        $result = $this->store->fetchOne(['id' => $id]);

        return $this->mapToEntity($result);
    }

    public function findByEmail(EmailType $email): AccountActivationCollectionInterface
    {
        $result = $this->store->fetchMany(['email' => $email]);

        return $this->mapToCollection($result);
    }

    public function findOneByToken(string $token): ?AccountActivationInterface
    {
        $result = $this->store->fetchOne(['token' => $token]);

        return $this->mapToEntity($result);
    }

    public function findAll(): AccountActivationCollectionInterface
    {
        $result = $this->store->fetchAll();

        return $this->mapToCollection($result);
    }

    public function deleteById(int $id): true
    {
        return $this->store->remove(['id' => $id]);
    }

    public function deleteByEmail(EmailType $email): true
    {
        return $this->store->remove(['email' => $email]);
    }
}
