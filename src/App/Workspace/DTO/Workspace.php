<?php declare(strict_types=1);

namespace ownHackathon\Workspace\DTO;

readonly class Workspace
{
    public function __construct(
        public string $name,
        public string $description,
        public string $owner,
        public string $ownerUuid,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['description'],
            $data['owner'],
            $data['ownerUuid']
        );
    }
}
