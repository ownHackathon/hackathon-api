<?php declare(strict_types=1);

namespace UnitTest\Mock\Validator;

use Exdrals\Identity\Infrastructure\Validator\AuthenticationValidator;
use Exdrals\Mailing\Infrastructure\Validator\Input\EmailInput;
use Exdrals\Identity\Infrastructure\Validator\Input\PasswordInput;

class MockAuthenticationValidatorFailed extends AuthenticationValidator
{
    public function __construct()
    {
        parent::__construct(new EmailInput(), new PasswordInput());
    }

    public function isValid($context = null)
    {
        return false;
    }
}
