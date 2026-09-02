<?php declare(strict_types=1);

namespace ownHackathon\Core\Observability;

use function filter_var;
use function str_contains;
use function strrpos;
use function substr;
use function trim;

use const FILTER_FLAG_IPV4;
use const FILTER_VALIDATE_IP;

/**
 * Maskiert IP-Adressen (letztes Oktett / letzte Gruppe), um die
 * Personenbeziehbarkeit im Sinne der DSGVO zu reduzieren.
 */
final class IpMasker
{
    public const string UNKNOWN = 'unknown';

    public static function mask(?string $ip): string
    {
        $ip = trim((string) $ip);
        if ($ip === '') {
            return self::UNKNOWN;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            $parts[3] = 'x';

            return implode('.', $parts);
        }

        if (filter_var($ip, FILTER_VALIDATE_IP) && str_contains($ip, ':')) {
            $position = strrpos($ip, ':');
            $prefix = substr($ip, 0, $position + 1);

            return $prefix . 'x';
        }

        return $ip;
    }
}
