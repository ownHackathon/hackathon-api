<?php declare(strict_types=1);

namespace ownHackathon\Workspace\DTO;

readonly class Workspace
{
    public function __construct(
        public string $name,
        public string $description,
        public string $owner,
        public string $ownerUuid,
        public string $details,
        public int $visibility,
        public string $createdAt,
        public string $updatedAt,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? '',
            owner: $data['owner'],
            ownerUuid: $data['ownerUuid'],
            details: $data['details'],
            visibility: $data['visibility'],
            createdAt: $data['createdAt'],
            updatedAt: $data['updatedAt'],
        );
    }
}
