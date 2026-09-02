<?php declare(strict_types=1);

namespace ownHackathon\Core\Observability;

use function mb_strtolower;
use function str_contains;

/**
 * Liefert ein grobes, nicht-personenbezogenes Kurzmerkmal des Clients
 * (Browser-/OS-Typ) aus einem User-Agent-Header.
 */
final class UserAgentSummarizer
{
    public const string UNKNOWN = 'unknown';
    public const string BROWSER = 'browser';

    public static function summarize(?string $userAgent): string
    {
        $ua = mb_strtolower((string) $userAgent);

        if ($ua === '') {
            return self::UNKNOWN;
        }

        if (str_contains($ua, 'curl/')) {
            return 'curl';
        }

        if (str_contains($ua, 'wget')) {
            return 'wget';
        }

        if (str_contains($ua, 'python-requests') || str_contains($ua, 'python-urllib')) {
            return 'python';
        }

        if (str_contains($ua, 'httpclient') || str_contains($ua, 'apache-httpclient')) {
            return 'httpclient';
        }

        if (str_contains($ua, 'postman')) {
            return 'postman';
        }

        if (str_contains($ua, 'insomnia')) {
            return 'insomnia';
        }

        if (str_contains($ua, 'mobile')) {
            return self::BROWSER . '-mobile';
        }

        return self::BROWSER;
    }
}
