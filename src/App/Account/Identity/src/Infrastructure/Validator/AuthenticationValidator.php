<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Validator;

use Exdrals\Identity\Infrastructure\Validator\Input\PasswordInput;
use Exdrals\Mailing\Infrastructure\Validator\Input\EmailInput;
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
