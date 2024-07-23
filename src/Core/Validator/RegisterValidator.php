<?php declare(strict_types=1);

namespace Core\Validator;

use Core\Validator\Input\EmailInput;
use Laminas\InputFilter\InputFilter;

class RegisterValidator extends InputFilter
{
    public function __construct(
        private readonly EmailInput $emailInput,
    ) {
        $this->add($this->emailInput);
    }
}
