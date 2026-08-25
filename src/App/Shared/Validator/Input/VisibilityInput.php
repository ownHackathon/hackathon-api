<?php declare(strict_types=1);

namespace ownHackathon\Shared\Validator\Input;

use Laminas\Filter\Digits;
use Laminas\Filter\StringTrim;
use Laminas\InputFilter\Input;
use Laminas\Validator\NumberComparison;

class VisibilityInput extends Input
{
    public function __construct()
    {
        parent::__construct('visibility');

        $this->setRequired(true);
        $this->setFallbackValue(5);
        $this->setAllowEmpty(false);

        $this->getFilterChain()->attachByName(StringTrim::class);
        $this->getFilterChain()->attachByName(Digits::class);

        $this->getValidatorChain()->attachByName(
            NumberComparison::class,
            [
                'min' => 1,
                'max' => 5,
            ],
        );
    }
}
