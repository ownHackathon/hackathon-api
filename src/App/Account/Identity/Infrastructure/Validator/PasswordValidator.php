<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Validator;

use Laminas\InputFilter\InputFilter;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\Input\PasswordInput;

final class PasswordValidator extends InputFilter
{
    public function __construct(
        private readonly PasswordInput $passwordInput,
    ) {
        $this->add($this->passwordInput);
    }
}
