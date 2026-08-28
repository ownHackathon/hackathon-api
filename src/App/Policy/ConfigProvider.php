<?php declare(strict_types=1);

namespace ownHackathon\App\Policy;

use Laminas\ServiceManager\Factory\InvokableFactory;
use ownHackathon\App\Policy\Domain\VisibilityPolicy;
use ownHackathon\App\Policy\Domain\VisibilityPolicyInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases' => [
                VisibilityPolicyInterface::class => VisibilityPolicy::class,
            ],
            'factories' => [
                VisibilityPolicy::class => InvokableFactory::class,
            ],
        ];
    }
}
