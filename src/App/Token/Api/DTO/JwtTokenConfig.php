<?php declare(strict_types=1);

namespace App\Token\Api\DTO;

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

    public static function fromArray(
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
            iss: $config['iss'],
            aud: $config['aud'],
            duration: (int)$config['duration'],
            algorithmus: $config['algorithmus'],
            key: $config['key'],
        );
    }
}
