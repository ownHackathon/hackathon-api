<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Validator;

use ownHackathon\App\Mailing\Infrastructure\Validator\Input\EmailInput;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\Input\PasswordInput;
use Laminas\InputFilter\InputFilter;

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
