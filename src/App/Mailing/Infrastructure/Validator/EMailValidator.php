<?php declare(strict_types=1);

namespace ownHackathon\App\Mailing\Infrastructure\Validator;

use Laminas\InputFilter\InputFilter;
use ownHackathon\App\Mailing\Infrastructure\Validator\Input\EmailInput;

final class EMailValidator extends InputFilter
{
    public function __construct(
        private readonly EmailInput $emailInput,
    ) {
        $this->add($this->emailInput);
    }
}
