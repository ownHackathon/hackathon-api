<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Validator;

use Laminas\InputFilter\InputFilter;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\Input\AccountNameInput;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\Input\PasswordInput;

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
