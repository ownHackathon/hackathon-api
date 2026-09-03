<?php declare(strict_types=1);

namespace App\Policy;

use Laminas\ServiceManager\Factory\InvokableFactory;
use App\Policy\Domain\VisibilityPolicy;
use App\Policy\Domain\VisibilityPolicyInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'aliases' => [
                    VisibilityPolicyInterface::class => VisibilityPolicy::class,
                ],
                'factories' => [
                    VisibilityPolicy::class => InvokableFactory::class,
                ],
            ],
        ];
    }
}