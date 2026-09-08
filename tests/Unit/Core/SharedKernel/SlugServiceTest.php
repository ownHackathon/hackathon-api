<?php declare(strict_types=1);

namespace Tests\Unit\Core\SharedKernel;

use Core\SharedKernel\Utils\SlugService;

use function expect;
use function test;

test('slug service converts mixed-case strings with separators to a slug', function (): void {
    $slugService = new SlugService();

    expect($slugService->getSlugFromString('Mein Berlin Workshop'))->toBe('mein-berlin-workshop')
        ->and($slugService->getSlugFromString('My_Berlin_Workshop'))->toBe('my-berlin-workshop');
});

test('slug service replaces non-alphanumeric characters and collapses separators', function (): void {
    $slugService = new SlugService();

    expect($slugService->getSlugFromString('Workshop!! 2026 (Sommer)'))->toBe('workshop-2026-sommer')
        ->and($slugService->getSlugFromString('a---b'))->toBe('a-b');
});

test('slug service trims leading and trailing separators', function (): void {
    $slugService = new SlugService();

    expect($slugService->getSlugFromString('-Berlin-'))->toBe('berlin');
});