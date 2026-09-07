<?php declare(strict_types=1);

namespace App\Token\Infrastructure\Persistence\Repository;

use App\Token\Domain\Entity\TokenCollectionInterface;
use App\Token\Domain\Entity\TokenInterface;
use App\Token\Domain\Repository\TokenRepositoryInterface;
use App\Token\Infrastructure\Hydrator\TokenHydratorInterface;
use App\Token\Infrastructure\Persistence\Table\TokenStoreInterface;
use Core\Persistence\Repository\AbstractRepository;
use Override;

readonly final class TokenRepository extends AbstractRepository implements TokenRepositoryInterface
{
    public function __construct(
        public TokenStoreInterface $store,
        public TokenHydratorInterface $hydrator,
    ) {
    }

    #[Override]
    public function insert(TokenInterface $data): int
    {
        $data = $this->hydrator->extract($data);

        return $this->store->persist($data);
    }

    #[Override]
    public function update(TokenInterface $data): true
    {
        $data = $this->hydrator->extract($data);

        return $this->store->update($data['id'], $data);
    }

    #[Override]
    public function findOneById(int $id): TokenInterface
    {
        $result = $this->store->fetchOne(['id' => $id]);

        return $this->mapToEntity($result);
    }

    #[Override]
    public function findByAccountId(int $accountId): TokenCollectionInterface
    {
        $result = $this->store->fetchMany(['accountId' => $accountId]);

        return $this->mapToCollection($result);
    }

    #[Override]
    public function findOneByToken(string $token): TokenInterface
    {
        $result = $this->store->fetchOne(['token' => $token]);

        return $this->mapToEntity($result);
    }

    #[Override]
    public function findAll(): TokenCollectionInterface
    {
        $result = $this->store->fetchAll();

        return $this->mapToCollection($result);
    }

    #[Override]
    public function deleteById(int $id): true
    {
        return $this->store->remove(['id' => $id]);
    }

    #[Override]
    public function deleteByAccountId(int $accountId): true
    {
        return $this->store->remove(['accountId' => $accountId]);
    }
}
