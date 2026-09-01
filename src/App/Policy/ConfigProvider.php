<?php declare(strict_types=1);

namespace ownHackathon\App\Policy;

use Laminas\ServiceManager\Factory\InvokableFactory;
use ownHackathon\App\Policy\Domain\VisibilityPolicy;
use ownHackathon\App\Policy\Domain\VisibilityPolicyInterface;
use ownHackathon\App\Policy\Http\Validator\Input\VisibilityInput;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'aliases' => [
                    VisibilityPolicyInterface::class => VisibilityPolicy::class,
                ],
                'invokables' => [
                    VisibilityInput::class,
                ],
                'factories' => [
                    VisibilityPolicy::class => InvokableFactory::class,
                ],
            ],
        ];
    }
}
