<?php declare(strict_types=1);

use Mezzio\Cors\Configuration\ConfigurationInterface;

return [
    ConfigurationInterface::CONFIGURATION_IDENTIFIER => [
        'allowed_origins' => [ConfigurationInterface::ANY_ORIGIN],
        'allowed_headers' => ['x-ident', 'Authorization', 'Authentication', 'Content-Type'], // No custom headers allowed
        'allowed_max_age' => '3600', // 60 minutes
        'credentials_allowed' => true, // Disallow cookies
        'exposed_headers' => [], // No headers are exposed
    ],
];
