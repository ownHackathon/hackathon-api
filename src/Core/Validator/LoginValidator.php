<?php declare(strict_types=1);

namespace Core\Validator;

use Core\Validator\Input\PasswordInput;
use Core\Validator\Input\UsernameInput;
use Laminas\InputFilter\InputFilter;

class LoginValidator extends InputFilter
{
    public function __construct(
        private readonly UsernameInput $usernameInput,
        private readonly PasswordInput $passwordInput,
    ) {
        $this->add($this->usernameInput);
        $this->add($this->passwordInput);
    }
}
