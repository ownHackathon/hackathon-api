<?php declare(strict_types=1);

namespace ownHackathon\App\Validator;

use Laminas\InputFilter\InputFilter;
use ownHackathon\App\Validator\Input\EmailInput;
use ownHackathon\App\Validator\Input\PasswordInput;

class AuthenticationValidator extends InputFilter
{
    public function __construct(
        readonly private EmailInput $emailInput,
        readonly private PasswordInput $passwordInput,
    ) {
        $this->add($this->emailInput);
        $this->add($this->passwordInput);
    }
}
