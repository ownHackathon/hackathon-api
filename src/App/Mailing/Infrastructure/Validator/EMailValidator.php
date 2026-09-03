<?php declare(strict_types=1);

namespace App\Mailing\Infrastructure\Validator;

use App\Mailing\Infrastructure\Validator\Input\EmailInput;
use Laminas\InputFilter\InputFilter;

final class EMailValidator extends InputFilter
{
    public function __construct(
        private readonly EmailInput $emailInput,
    ) {
        $this->add($this->emailInput);
    }
}
