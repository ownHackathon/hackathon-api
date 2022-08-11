<?php declare(strict_types=1);

namespace Authentication\Validator;

use App\Validator\Input\EmailInput;
use App\Validator\Input\PasswordInput;
use App\Validator\Input\UsernameInput;
use Laminas\InputFilter\InputFilter;

class RegisterValidator extends InputFilter
{
    public function __construct(
        private readonly UsernameInput $usernameInput,
        private readonly PasswordInput $passwordInput,
        private readonly EmailInput $emailInput,
    ) {
        $this->add($this->usernameInput);
        $this->add($this->passwordInput);
        $this->add($this->emailInput);
    }
}
