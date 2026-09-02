<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Persistence\Repository;

use ownHackathon\App\Account\Identity\Domain\AccountActivationCollectionInterface;
use ownHackathon\App\Account\Identity\Domain\AccountActivationInterface;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountActivationRepositoryInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountActivationHydratorInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Table\AccountActivationStoreInterface;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\Core\Persistence\Hydrator\HydratorInterface;
use ownHackathon\Core\Persistence\Repository\AbstractRepository;
use ownHackathon\Core\Persistence\Store\StoreInterface;

readonly final class AccountActivationRepository extends AbstractRepository implements AccountActivationRepositoryInterface
{
    public function __construct(
        private AccountActivationStoreInterface $store,
        private AccountActivationHydratorInterface $hydrator,
    ) {
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

    protected function getHydrator(): HydratorInterface
    {
        return $this->hydrator;
    }

    protected function getStore(): StoreInterface
    {
        return $this->store;
    }
}
