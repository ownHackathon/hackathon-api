<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Validator;

use DateTime;
use Exception;
use Laminas\Validator\AbstractValidator;

final class DateLessNow extends AbstractValidator
{
    public const string VALID_DATE = 'valid_date';

    protected array $messageTemplates
        = [
            self::VALID_DATE => 'Date is in the past',
        ];

    #[\Override]
    public function isValid(mixed $value): bool
    {
        $dateNow = new DateTime();

        try {
            $dateValue = new DateTime($value);
        } catch (Exception $exception) {
            return false;
        }

        if ($dateValue <= $dateNow) {
            $this->error(self::VALID_DATE);
            return false;
        }

        $this->setValue($value);

        return true;
    }
}
