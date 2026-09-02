<?php declare(strict_types=1);

namespace ownHackathon\App\Policy\Http\Validator\Input;

use Laminas\Filter\Digits;
use Laminas\Filter\StringTrim;
use Laminas\InputFilter\Input;
use Laminas\Validator\NumberComparison;
use ownHackathon\App\Policy\Domain\Enum\Visibility;

final class VisibilityInput extends Input
{
    public function __construct()
    {
        parent::__construct('visibility');

        $this->setRequired(true);
        $this->setAllowEmpty(false);

        $this->getFilterChain()->attachByName(StringTrim::class);
        $this->getFilterChain()->attachByName(Digits::class);

        $this->getValidatorChain()->attachByName(
            NumberComparison::class,
            [
                'min' => Visibility::UNLISTED->value,
                'max' => Visibility::PUBLIC->value,
            ],
        );
    }
}
