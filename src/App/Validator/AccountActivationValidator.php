<?php declare(strict_types=1);

namespace App\Validator;

use Laminas\InputFilter\InputFilter;
use App\Validator\Input\AccountNameInput;
use App\Validator\Input\PasswordInput;

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
