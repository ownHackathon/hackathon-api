<?php declare(strict_types=1);

namespace App\Token\Infrastructure\Hydrator;

use App\Token\Application\Port\TokenLoggerInterface;
use App\Token\Domain\Enum\TokenType;
use App\Token\Domain\Message\TokenLogMessage;
use App\Token\Domain\Token;
use App\Token\Domain\TokenCollection;
use App\Token\Domain\TokenCollectionInterface;
use App\Token\Domain\TokenInterface;
use Core\Clock\DateTimeFormat;
use DateTimeImmutable;
use Ramsey\Uuid\UuidFactoryInterface;

readonly final class TokenHydrator implements TokenHydratorInterface
{
    public function __construct(
        private UuidFactoryInterface $uuid,
        private TokenLoggerInterface $logger,
    ) {
    }

    #[\Override]
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

    #[\Override]
    public function hydrateCollection(array $data): TokenCollectionInterface
    {
        $collection = new TokenCollection();

        foreach ($data as $entity) {
            try {
                $collection[] = $this->hydrate($entity);
            } catch (\Throwable $exception) {
                $this->logger->warning(TokenLogMessage::TOKEN_DATA_SKIPPED, [
                    'tokenId' => $entity['id'] ?? null,
                    'exception' => $exception,
                ]);
            }
        }

        return $collection;
    }

    #[\Override]
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

    #[\Override]
    public function extractCollection(TokenCollectionInterface $collection): array
    {
        $data = [];

        foreach ($collection as $entity) {
            $data[] = $this->extract($entity);
        }

        return $data;
    }
}
