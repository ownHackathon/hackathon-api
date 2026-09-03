<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Persistence\Repository;

use App\Account\Identity\Domain\AccountActivationCollectionInterface;
use App\Account\Identity\Domain\AccountActivationInterface;
use App\Account\Identity\Domain\Repository\AccountActivationRepositoryInterface;
use App\Account\Identity\Infrastructure\Hydrator\AccountActivationHydratorInterface;
use App\Account\Identity\Infrastructure\Persistence\Table\AccountActivationStoreInterface;
use App\Mailing\Domain\EmailType;
use Core\Persistence\Hydrator\HydratorInterface;
use Core\Persistence\Repository\AbstractRepository;
use Core\Persistence\Store\StoreInterface;

readonly final class AccountActivationRepository extends AbstractRepository implements AccountActivationRepositoryInterface
{
    public function __construct(
        private AccountActivationStoreInterface $store,
        private AccountActivationHydratorInterface $hydrator,
    ) {
    }

    #[\Override]
    public function insert(AccountActivationInterface $data): int
    {
        $data = $this->hydrator->extract($data);

        return $this->store->persist($data);
    }

    #[\Override]
    public function update(AccountActivationInterface $data): true
    {
        $data = $this->hydrator->extract($data);

        return $this->store->update($data['id'], $data);
    }

    #[\Override]
    public function findOneById(int $id): AccountActivationInterface
    {
        $result = $this->store->fetchOne(['id' => $id]);

        return $this->mapToEntity($result);
    }

    #[\Override]
    public function findByEmail(EmailType $email): AccountActivationCollectionInterface
    {
        $result = $this->store->fetchMany(['email' => $email]);

        return $this->mapToCollection($result);
    }

    #[\Override]
    public function findOneByToken(string $token): AccountActivationInterface
    {
        $result = $this->store->fetchOne(['token' => $token]);

        return $this->mapToEntity($result);
    }

    #[\Override]
    public function findAll(): AccountActivationCollectionInterface
    {
        $result = $this->store->fetchAll();

        return $this->mapToCollection($result);
    }

    #[\Override]
    public function deleteById(int $id): true
    {
        return $this->store->remove(['id' => $id]);
    }

    #[\Override]
    public function deleteByEmail(EmailType $email): true
    {
        return $this->store->remove(['email' => $email]);
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
