<?php declare(strict_types=1);

namespace App\Validator;

use App\Validator\Input\PasswordInput;
use Laminas\InputFilter\InputFilter;

class UserPasswordChangeValidator extends InputFilter
{
    public function __construct(
        private readonly PasswordInput $passwordInput,
    ) {
        $this->add($this->passwordInput);
    }
}
