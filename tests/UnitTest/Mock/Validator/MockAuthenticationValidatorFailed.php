<?php declare(strict_types=1);

namespace UnitTest\Mock\Validator;

use App\Validator\AuthenticationValidator;
use App\Validator\Input\EmailInput;
use App\Validator\Input\PasswordInput;

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
