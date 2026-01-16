<?php declare(strict_types=1);

namespace App\Validator;

use Laminas\InputFilter\InputFilter;
use App\Validator\Input\PasswordInput;

class PasswordValidator extends InputFilter
{
    public function __construct(
        private readonly PasswordInput $passwordInput,
    ) {
        $this->add($this->passwordInput);
    }
}
