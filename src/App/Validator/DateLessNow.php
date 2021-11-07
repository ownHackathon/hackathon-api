<?php declare(strict_types=1);

namespace App\Validator;

use DateTime;
use Laminas\Validator\AbstractValidator;

class DateLessNow extends AbstractValidator
{
    public const VALID_DATE = 'valid_date';

    protected array $messageTemplates = [
        self::VALID_DATE => "Date is in the past",
    ];

    public function isValid($value)
    {
        $this->setValue($value);

        $dateNow = new DateTime();
        $dateValue = new DateTime($value);

        if ($dateValue <= $dateNow) {
            $this->error(self::VALID_DATE);
            return false;
        }

        return true;
    }
}
