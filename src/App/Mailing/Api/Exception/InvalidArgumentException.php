<?php declare(strict_types=1);

namespace App\Mailing\Api\Exception;

// @todo Überlegen, ob Exceptions im Api-Layer die richtige Stelle sind.
//       Alternative: EmailType gibt ein Result-Object statt Exception zurück,
//       sodass der Api-Layer grundsätzlich keine Exceptions wirft.
final class InvalidArgumentException extends \InvalidArgumentException
{
}
