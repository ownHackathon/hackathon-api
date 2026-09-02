<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\ClientIdentification;

use ownHackathon\App\Account\Identity\DTO\Client\ClientIdentificationData;

use function hash;
use function serialize;

readonly final class ClientIdentificationService
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
