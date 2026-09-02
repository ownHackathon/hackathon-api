<?php declare(strict_types=1);

namespace Tests\Integration\App\Account\Identity;

use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\Core\Http\Enum\RouteIdent;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;
use Tests\Integration\Mock\ArrayLogger;

use function expect;
use function test;

test('guest request is logged as an account interaction without plaintext', function (): void {
    ArrayLogger::reset();

    $request = $this->createGetRequest('/api/ping');

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(200);

    $records = ArrayLogger::all();
    expect($records)->not->toBeEmpty();

    $interaction = null;
    foreach ($records as $record) {
        if ($record['message'] === IdentityLogMessage::ACTIVITY_INTERACTION) {
            $interaction = $record;
        }
    }

    expect($interaction)->not->toBeNull();
    $context = $interaction['context'];
    expect($context)->toHaveKeys([
        'accountId', 'accountUuid', 'guest', 'route', 'method', 'uri',
        'status', 'duration', 'ip', 'userAgent', 'clientIdentHash', 'correlation_id',
    ])
        ->and($context['guest'])->toBeTrue()
        ->and($context['route'])->toBe(RouteIdent::PING->value);
});

test('successful login logs a login success event with account ids', function (): void {
    ArrayLogger::reset();

    $account = AccountFactory::create();
    $request = $this->createJsonPostRequest(
        '/api/account/authentication',
        [
            'email' => $account['email'],
            'password' => $account['password'],
        ]
    );

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(200);

    $records = ArrayLogger::all();
    $login = null;
    foreach ($records as $record) {
        if ($record['message'] === IdentityLogMessage::ACTIVITY_LOGIN_SUCCESS) {
            $login = $record;
        }
    }

    expect($login)->not->toBeNull();
    expect($login['context'])->toHaveKeys(['accountId', 'accountUuid', 'clientIdentHash'])
        ->and($login['context']['accountId'])->toBe((int) $account['id']);
});
