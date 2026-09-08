<?php declare(strict_types=1);

namespace Core\SharedKernel\Utils;

use function preg_replace;
use function str_replace;
use function strtolower;
use function trim;

readonly final class SlugService
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
