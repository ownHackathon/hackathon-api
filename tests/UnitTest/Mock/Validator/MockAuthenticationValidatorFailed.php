<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Validator;

use ownHackathon\App\Validator\AuthenticationValidator;
use ownHackathon\App\Validator\Input\EmailInput;
use ownHackathon\App\Validator\Input\PasswordInput;

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
