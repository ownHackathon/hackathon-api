<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Validator;

use Exdrals\Identity\Infrastructure\Validator\Input\AccountNameInput;
use Exdrals\Identity\Infrastructure\Validator\Input\PasswordInput;
use Laminas\InputFilter\InputFilter;

class AccountActivationValidator extends InputFilter
{
    public function __construct(
        readonly private AccountNameInput $accountNameInput,
        readonly private PasswordInput $passwordInput,
    ) {
        $this->add($this->accountNameInput);
        $this->add($this->passwordInput);
    }
}
