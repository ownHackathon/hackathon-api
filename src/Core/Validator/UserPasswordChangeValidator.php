<?php declare(strict_types=1);

namespace Core\Validator;

use Core\Validator\Input\PasswordInput;
use Laminas\InputFilter\InputFilter;

class UserPasswordChangeValidator extends InputFilter
{
    public function __construct(
        private readonly PasswordInput $passwordInput,
    ) {
        $this->add($this->passwordInput);
    }
}
