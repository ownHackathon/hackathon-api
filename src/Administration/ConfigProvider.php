<?php declare(strict_types=1);

namespace Administration;

use App\Service\UserService;
use App\Validator\Input\EmailInput;
use App\Validator\Input\PasswordInput;
use App\Validator\Input\UsernameInput;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Mezzio\Template\TemplateRendererInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            ConfigAbstractFactory::class => $this->getAbstractFactoryConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'invokables' => [

            ],
            'factories' => [
                Handler\IndexHandler::class => ConfigAbstractFactory::class,
                Handler\UserRegisterHandler::class => ConfigAbstractFactory::class,
                Handler\UserRegisterSubmitHandler::class => ConfigAbstractFactory::class,

                Middleware\UserRegisterMiddleware::class => ConfigAbstractFactory::class,
                Middleware\UserRegisterValidationMiddleware::class => ConfigAbstractFactory::class,

                Validator\RegisterValidator::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Handler\IndexHandler::class => [
                TemplateRendererInterface::class,
            ],
            Handler\UserRegisterHandler::class => [
                TemplateRendererInterface::class,
            ],
            Handler\UserRegisterSubmitHandler::class => [
                TemplateRendererInterface::class,
            ],

            Middleware\UserRegisterMiddleware::class => [
                UserService::class,
                ClassMethodsHydrator::class,
            ],
            Middleware\UserRegisterValidationMiddleware::class => [
                Validator\RegisterValidator::class,
            ],

            Validator\RegisterValidator::class => [
                UsernameInput::class,
                PasswordInput::class,
                EmailInput::class,
            ],
        ];
    }
}
