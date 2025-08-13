<?php declare(strict_types=1);

namespace ownHackathon\App\Validator;

use Laminas\InputFilter\InputFilter;
use ownHackathon\App\Validator\Input\EmailInput;

class EMailValidator extends InputFilter
{
    public function __construct(
        private readonly EmailInput $emailInput,
    ) {
        $this->add($this->emailInput);
    }
}
