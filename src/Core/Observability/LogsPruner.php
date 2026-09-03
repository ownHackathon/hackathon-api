<?php declare(strict_types=1);

namespace Core\Observability;

use DateInterval;
use DateTimeImmutable;

use function is_dir;
use function is_link;
use function rmdir;
use function rtrim;
use function scandir;
use function sprintf;
use function str_contains;
use function str_starts_with;
use function strlen;

/**
 * Entfernt datumsbasierte Log-Verzeichnisse unterhalb einer Log-Root,
 * deren Name älter als die konfigurierte Aufbewahrungsfrist ist.
 * Dient der DSGVO-konformen Löschung (Art. 5 Abs. 1 lit. e).
 */
final class LogsPruner
{
    public static function prune(string $logRoot, int $retentionDays): int
    {
        $logRoot = rtrim($logRoot, '/');
        $entries = scandir($logRoot);

        if ($entries === false) {
            return 0;
        }

        $cutoff = new DateTimeImmutable()->sub(new DateInterval(sprintf('P%dD', $retentionDays)));
        $removed = 0;

        foreach ($entries as $entry) {
            if (str_starts_with($entry, '.')) {
                continue;
            }

            $directory = $logRoot . '/' . $entry;

            if (!is_dir($directory) || is_link($directory)) {
                continue;
            }

            $date = self::parseDate($entry);
            if ($date === null) {
                continue;
            }

            if ($date >= $cutoff || !rmdir($directory)) {
                continue;
            }

            $removed++;
        }

        return $removed;
    }

    private static function parseDate(string $entry): ?DateTimeImmutable
    {
        if (!self::isDateDirectory($entry)) {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat('Y-m-d', $entry);

        return $date === false ? null : $date;
    }

    private static function isDateDirectory(string $entry): bool
    {
        if (str_contains($entry, '/') || str_contains($entry, '\\')) {
            return false;
        }

        return strlen($entry) === 10 && $entry[4] === '-' && $entry[7] === '-';
    }
}
