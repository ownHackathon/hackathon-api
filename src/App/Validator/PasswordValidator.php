<?php declare(strict_types=1);

namespace ownHackathon\App\Validator;

use Laminas\InputFilter\InputFilter;
use ownHackathon\App\Validator\Input\PasswordInput;

class PasswordValidator extends InputFilter
{
    public function __construct(
        private readonly PasswordInput $passwordInput,
    ) {
        $this->add($this->passwordInput);
    }
}
