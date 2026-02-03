<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Hydrator\Token;

use DateTimeImmutable;
use Exdrals\Identity\Domain\Token;
use Exdrals\Identity\Domain\TokenCollection;
use Exdrals\Shared\Domain\Enum\DateTimeFormat;
use Exdrals\Shared\Domain\Enum\Token\TokenType;
use Exdrals\Shared\Domain\Token\TokenCollectionInterface;
use Exdrals\Shared\Domain\Token\TokenInterface;
use Exdrals\Shared\Infrastructure\Hydrator\Token\TokenHydratorInterface;
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
