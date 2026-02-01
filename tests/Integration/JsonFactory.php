<?php declare(strict_types=1);

namespace Tests\Integration;

use Psr\Http\Message\ResponseInterface;

readonly class JsonFactory
{
    public static function create(ResponseInterface $response): ?array
    {
        return json_decode((string)$response->getBody(), true);
    }
}
