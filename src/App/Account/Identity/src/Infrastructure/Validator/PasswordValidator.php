<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Validator;

use Exdrals\Account\Identity\Infrastructure\Validator\Input\PasswordInput;
use Laminas\InputFilter\InputFilter;

class PasswordValidator extends InputFilter
{
    public function __construct(
        private readonly PasswordInput $passwordInput,
    ) {
        $this->add($this->passwordInput);
    }
}
