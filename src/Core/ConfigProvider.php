<?php declare(strict_types=1);

namespace Core;

use Core\Hydrator\ClassMethodsHydratorFactory;
use Core\Hydrator\DateTimeFormatterStrategyFactory;
use Core\Hydrator\DateTimeImmutableFormatterStrategyFactory;
use Core\Hydrator\NullableStrategyFactory;
use Core\Hydrator\ReflectionHydrator;
use Core\Listener\LoggingErrorListener;
use Core\Listener\LoggingErrorListenerFactory;
use Core\Validator\EventCreateValidator;
use Core\Validator\Input\EmailInput;
use Core\Validator\Input\Event\EventDescriptionInput;
use Core\Validator\Input\Event\EventDurationInput;
use Core\Validator\Input\Event\EventStartTimeInput;
use Core\Validator\Input\Event\EventTextInput;
use Core\Validator\Input\Event\EventTitleInput;
use Core\Validator\Input\PasswordInput;
use Core\Validator\Input\Topic\TopicDescriptionInput;
use Core\Validator\Input\Topic\TopicInput;
use Core\Validator\Input\UsernameInput;
use Core\Validator\LoginValidator;
use Core\Validator\PasswordForgottenEmailValidator;
use Core\Validator\RegisterValidator;
use Core\Validator\TopicCreateValidator;
use Core\Validator\UserPasswordChangeValidator;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\DateTimeImmutableFormatterStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;

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
                EmailInput::class,
                EventDescriptionInput::class,
                EventDurationInput::class,
                EventStartTimeInput::class,
                EventTextInput::class,
                EventTitleInput::class,
                PasswordInput::class,
                ReflectionHydrator::class,
                TopicDescriptionInput::class,
                TopicInput::class,
                UsernameInput::class,
            ],
            'aliases' => [

            ],
            'factories' => [
                ClassMethodsHydrator::class => ClassMethodsHydratorFactory::class,
                DateTimeFormatterStrategy::class => DateTimeFormatterStrategyFactory::class,
                DateTimeImmutableFormatterStrategy::class => DateTimeImmutableFormatterStrategyFactory::class,
                NullableStrategy::class => NullableStrategyFactory::class,

                LoggingErrorListener::class => LoggingErrorListenerFactory::class,

                EventCreateValidator::class => ConfigAbstractFactory::class,
                LoginValidator::class => ConfigAbstractFactory::class,
                PasswordForgottenEmailValidator::class => ConfigAbstractFactory::class,
                RegisterValidator::class => ConfigAbstractFactory::class,
                TopicCreateValidator::class => ConfigAbstractFactory::class,
                UserPasswordChangeValidator::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            EventCreateValidator::class => [
                EventTitleInput::class,
                EventDescriptionInput::class,
                EventTextInput::class,
                EventStartTimeInput::class,
                EventDurationInput::class,
            ],
            LoginValidator::class => [
                UsernameInput::class,
                PasswordInput::class,
            ],
            PasswordForgottenEmailValidator::class => [
                EmailInput::class,
            ],
            RegisterValidator::class => [
                EmailInput::class,
            ],
            TopicCreateValidator::class => [
                TopicInput::class,
                TopicDescriptionInput::class,
            ],
            UserPasswordChangeValidator::class => [
                PasswordInput::class,
            ],
        ];
    }
}
