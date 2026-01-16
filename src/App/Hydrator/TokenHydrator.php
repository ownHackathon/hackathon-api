<?php declare(strict_types=1);

namespace ownHackathon\App\Hydrator;

use DateTimeImmutable;
use Ramsey\Uuid\UuidFactoryInterface;
use ownHackathon\App\Entity\Token;
use ownHackathon\App\Entity\TokenCollection;
use ownHackathon\App\Enum\TokenType;
use ownHackathon\Core\Entity\TokenCollectionInterface;
use ownHackathon\Core\Entity\TokenInterface;
use ownHackathon\Core\Enum\App\DateTimeFormat;

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
            tokenType: TokenType::EMail,
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
            'id' => $object->getId(),
            'accountId' => $object->getAccountId(),
            'tokenType' => $object->getTokenType()->value,
            'token' => $object->getToken()->getHex(),
            'createdAt' => $object->getCreatedAt()->format(DateTimeFormat::DEFAULT->value),
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
