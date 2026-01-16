<?php declare(strict_types=1);

namespace App\Validator;

use Laminas\InputFilter\InputFilter;
use App\Validator\Input\EmailInput;

class EMailValidator extends InputFilter
{
    public function __construct(
        private readonly EmailInput $emailInput,
    ) {
        $this->add($this->emailInput);
    }
}
