<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\DTO\Token;

use JetBrains\PhpStorm\ArrayShape;

readonly final class JwtTokenConfig
{
    public function __construct(
        public string $iss,
        public string $aud,
        public int $duration,
        public string $algorithmus,
        public string $key,
    ) {
    }

    /**
     * @param array{iss: string, aud: string, duration: int|string, algorithmus: string, key: string} $config
     */
    public static function createFromArray(
        #[ArrayShape([
            'iss' => 'string',
            'aud' => 'string',
            'duration' => 'int|string',
            'algorithmus' => 'string',
            'key' => 'string',
        ])]
        array $config,
    ): self {
        return new self(
            $config['iss'],
            $config['aud'],
            (int)$config['duration'],
            $config['algorithmus'],
            $config['key'],
        );
    }
}
