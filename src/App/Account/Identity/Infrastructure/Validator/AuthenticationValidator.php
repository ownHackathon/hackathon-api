<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Validator;

use App\Account\Identity\Infrastructure\Validator\Input\PasswordInput;
use App\Mailing\Infrastructure\Validator\Input\EmailInput;
use Laminas\InputFilter\InputFilter;

final class AuthenticationValidator extends InputFilter
{
    public function __construct(
        readonly private EmailInput $emailInput,
        readonly private PasswordInput $passwordInput,
    ) {
        $this->add($this->emailInput);
        $this->add($this->passwordInput);
    }
}
