<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Validator;

use Laminas\InputFilter\InputFilter;
use Exdrals\Identity\Infrastructure\Validator\Input\PasswordInput;

class PasswordValidator extends InputFilter
{
    public function __construct(
        private readonly PasswordInput $passwordInput,
    ) {
        $this->add($this->passwordInput);
    }
}
