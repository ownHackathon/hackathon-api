<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Validator;

use Exdrals\Account\Identity\Infrastructure\Validator\Input\EmailInput;
use Laminas\InputFilter\InputFilter;

class EMailValidator extends InputFilter
{
    public function __construct(
        private readonly EmailInput $emailInput,
    ) {
        $this->add($this->emailInput);
    }
}
