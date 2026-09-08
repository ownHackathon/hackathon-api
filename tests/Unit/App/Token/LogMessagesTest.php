<?php declare(strict_types=1);

namespace Tests\Unit\App\Token;

use App\Token\Domain\Message\TokenLogMessage;

use function expect;
use function test;

test('token log message constants are unique and non-empty', function (): void {
    expect(TokenLogMessage::TOKEN_DATA_SKIPPED)->not->toBe('');
});
