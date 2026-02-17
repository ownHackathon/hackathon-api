<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\ClientIdentification;

use Exdrals\Identity\DTO\Client\ClientIdentificationData;

use function hash;
use function serialize;

readonly class ClientIdentificationService
{
    public function getClientIdentificationHash(ClientIdentificationData $clientIdentificationData): string
    {
        return $this->getIdentificationHash($clientIdentificationData);
    }

    private function getIdentificationHash(ClientIdentificationData $clientIdentificationData): string
    {
        return hash('sha512', serialize($clientIdentificationData));
    }
}
