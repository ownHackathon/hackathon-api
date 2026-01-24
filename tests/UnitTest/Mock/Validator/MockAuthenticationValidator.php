<?php declare(strict_types=1);

namespace UnitTest\Mock\Validator;

use Exdrals\Mailing\Infrastructure\Validator\Input\EmailInput;
use Exdrals\Account\Identity\Infrastructure\Validator\AuthenticationValidator;
use Exdrals\Account\Identity\Infrastructure\Validator\Input\PasswordInput;

class MockAuthenticationValidator extends AuthenticationValidator
{
    public function __construct()
    {
        parent::__construct(new EmailInput(), new PasswordInput());
    }

    public function isValid($context = null)
    {
        return true;
    }
}
