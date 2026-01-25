<?php declare(strict_types=1);

namespace Exdrals\Shared\Infrastructure\Service;

use function str_replace;
use function strtolower;

readonly class SlugService
{
    public function getSlugFromString(string $data): string
    {
        return str_replace([' ', '_'], ['-'], strtolower($data));
    }
}
