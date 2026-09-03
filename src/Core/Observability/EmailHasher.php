<?php declare(strict_types=1);

namespace Core\Observability;

use function hash_hmac;
use function mb_strtolower;
use function trim;

/**
 * Erzeugt einen salted Hash einer E-Mail-Adresse, um wiederholte Versuche
 * für dieselbe (ggf. unbekannte) Adresse gruppieren zu können, ohne die
 * Adresse im Klartext zu loggen. Nicht zur Pseudonymisierung geeignet,
 * da der Werteraum von E-Mail-Adressen klein ist.
 */
final class EmailHasher
{
    public static function hash(string $email, string $salt): string
    {
        $normalized = mb_strtolower(trim($email));

        return hash_hmac('sha256', $normalized, $salt);
    }
}
