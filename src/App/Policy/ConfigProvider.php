<?php declare(strict_types=1);

namespace App\Policy;

use App\Policy\Application\VisibilityPolicy;
use App\Policy\Api\VisibilityPolicyInterface;
use Laminas\ServiceManager\Factory\InvokableFactory;

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