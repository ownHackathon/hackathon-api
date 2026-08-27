<?php declare(strict_types=1);

namespace ownHackathon\App\Token\Infrastructure\Hydrator;

use DateTimeImmutable;
use ownHackathon\App\Token\Domain\Enum\TokenType;
use ownHackathon\App\Token\Domain\Token;
use ownHackathon\App\Token\Domain\TokenCollection;
use ownHackathon\App\Token\Domain\TokenCollectionInterface;
use ownHackathon\App\Token\Domain\TokenInterface;
use ownHackathon\Core\Shared\Domain\Enum\DateTimeFormat;
use Ramsey\Uuid\UuidFactoryInterface;

readonly class TokenHydrator implements TokenHydratorInterface
{
    public function __construct(
        private UuidFactoryInterface $uuid,
    ) {
    }

    public function hydrate(array $data): TokenInterface
    {
        return new Token(
            id: $data['id'],
            accountId: $data['accountId'],
            tokenType: TokenType::from((int) $data['tokenType']),
            token: $this->uuid->fromString($data['token']),
            createdAt: new DateTimeImmutable($data['createdAt']),
        );
    }

    public function hydrateCollection(array $data): TokenCollectionInterface
    {
        $collection = new TokenCollection();

        foreach ($data as $entity) {
            $collection[] = $this->hydrate($entity);
        }

        return $collection;
    }

    public function extract(TokenInterface $object): array
    {
        return [
            'id' => $object->id,
            'accountId' => $object->accountId,
            'tokenType' => $object->tokenType->value,
            'token' => $object->token->toString(),
            'createdAt' => $object->createdAt->format(DateTimeFormat::DEFAULT->value),
        ];
    }

    public function extractCollection(TokenCollectionInterface $collection): array
    {
        $data = [];

        foreach ($collection as $entity) {
            $data[] = $this->extract($entity);
        }

        return $data;
    }
}
