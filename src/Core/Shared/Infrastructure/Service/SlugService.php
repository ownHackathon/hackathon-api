<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Infrastructure\Service;

use function str_replace;
use function strtolower;

readonly class SlugService
{
    public function getSlugFromString(string $data): string
    {
        $slug = strtolower($data);
        $slug = str_replace([' ', '_'], '-', $slug);
        $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
}
