<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Validator;

use App\Account\Identity\Infrastructure\Validator\Input\PasswordInput;
use Laminas\InputFilter\InputFilter;

final class PasswordValidator extends InputFilter
{
    public function __construct(
        private readonly PasswordInput $passwordInput,
    ) {
        $this->add($this->passwordInput);
    }
}
