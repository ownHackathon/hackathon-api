<?php declare(strict_types=1);

namespace ownHackathon\UnitTest;

use Psr\Http\Message\ResponseInterface;

use function json_decode;

trait JsonRequestHelper
{
    public function getContentAsJson(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
