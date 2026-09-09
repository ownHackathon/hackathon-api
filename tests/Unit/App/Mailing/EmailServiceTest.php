<?php declare(strict_types=1);

namespace Tests\Unit\App\Mailing;

use App\Mailing\Api\EmailType;
use App\Mailing\Api\Exception\InvalidArgumentException;
use App\Mailing\Infrastructure\Factory\EmailServiceFactory;
use App\Mailing\Infrastructure\Factory\MailFactory;
use App\Mailing\Infrastructure\Service\EmailService;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Symfony\Component\Mailer\Exception\InvalidArgumentException as SymfonyMailerInvalidArgumentException;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailerInterface;
use Symfony\Component\Mime\Email;

use function expect;
use function test;

test('email service sends a message with resolved recipient, sender and content', function (): void {
    $senderEmail = new EmailType('no-reply@example.com');
    $symfonyMailer = $this->createMock(SymfonyMailerInterface::class);

    $symfonyMailer->expects($this->once())->method('send')->with($this->callback(
        static function (Email $message) use ($senderEmail): bool {
            return $message->getTo()[0]->getAddress() === 'recipient@example.com'
                && $message->getFrom()[0]->getAddress() === $senderEmail->toString()
                && $message->getSubject() === 'Welcome'
                && $message->getTextBody() === 'plain'
                && $message->getHtmlBody() === '<p>html</p>';
        },
    ));

    $service = new EmailService($symfonyMailer, $senderEmail);

    $service->send(new EmailType('recipient@example.com'), 'plain', '<p>html</p>', 'Welcome');
});

test('email service factory builds an email service with the configured sender', function (): void {
    $container = $this->createMock(ContainerInterface::class);
    $symfonyMailer = $this->createMock(SymfonyMailerInterface::class);
    $container->method('get')->willReturnCallback(
        static function (string $id) use ($symfonyMailer): mixed {
            return match ($id) {
                SymfonyMailerInterface::class => $symfonyMailer,
                'config' => ['project' => ['senderEmail' => 'no-reply@example.com']],
                default => null,
            };
        },
    );

    $factory = new EmailServiceFactory();
    $service = $factory($container);

    expect($service)->toBeInstanceOf(EmailService::class);
});

test('email service forwards a mailer failure to the caller', function (): void {
    $senderEmail = new EmailType('no-reply@example.com');
    $symfonyMailer = $this->createMock(SymfonyMailerInterface::class);
    $symfonyMailer->method('send')->willThrowException(new RuntimeException('transport down'));

    $service = new EmailService($symfonyMailer, $senderEmail);

    expect(fn (): null => $service->send(
        new EmailType('recipient@example.com'),
        'plain',
        '<p>html</p>',
        'Welcome',
    ))->toThrow(RuntimeException::class, 'transport down');
});

test('email service factory rejects an invalid configured sender email', function (): void {
    $container = $this->createMock(ContainerInterface::class);
    $symfonyMailer = $this->createMock(SymfonyMailerInterface::class);
    $container->method('get')->willReturnCallback(
        static function (string $id) use ($symfonyMailer): mixed {
            return match ($id) {
                SymfonyMailerInterface::class => $symfonyMailer,
                'config' => ['project' => ['senderEmail' => 'not-an-email']],
                default => null,
            };
        },
    );

    $factory = new EmailServiceFactory();

    expect(fn (): EmailService => $factory($container))
        ->toThrow(InvalidArgumentException::class);
});

test('mail factory builds a mailer from the configured dsn', function (): void {
    $container = $this->createMock(ContainerInterface::class);
    $container->expects($this->once())->method('get')->with('config')->willReturn([
        'mailer' => ['dsn' => 'null://null'],
    ]);

    $factory = new MailFactory();
    $mailer = $factory($container);

    expect($mailer)->toBeInstanceOf(SymfonyMailerInterface::class);
});

test('mail factory rejects an invalid dsn scheme', function (): void {
    $container = $this->createMock(ContainerInterface::class);
    $container->expects($this->once())->method('get')->with('config')->willReturn([
        'mailer' => ['dsn' => 'not-a-valid-dsn'],
    ]);

    $factory = new MailFactory();

    expect(fn (): SymfonyMailerInterface => $factory($container))
        ->toThrow(SymfonyMailerInvalidArgumentException::class);
});
