<?php declare(strict_types=1);

namespace ownHackathon\FunctionalTest\Root\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use ownHackathon\Core\Message\DataType;
use ownHackathon\Core\Message\ResponseMessage;

trait InvalidEMailAddressProviderTrait
{
    #[DataProvider('invalidEMailAddressProvider')]
    public function testEMailValidationInvalid(string $email): void
    {
        $request = new ServerRequest(
            uri: '/api//account/password/forgotten',
            method: 'POST'
        );
        $request = $request->withParsedBody(['email' => $email]);
        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertThat($response, $this->bodyMatchesJson([
            'statusCode' => Assert::isType(DataType::INT),
            'message' => Assert::isType(DataType::STRING),
        ]));
        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(ResponseMessage::EMAIL_INVALID, $content['message']);
    }

    public static function invalidEMailAddressProvider(): array
    {
        return [
            'Missing @ symbol' => ['invalidemail.com'],
            'Missing domain' => ['user@'],
            'Missing local part' => ['@domain.com'],
            'Consecutive dots' => ['user..name@domain.com'],
            'Invalid character in local part' => ['user:name@domain.com'],
            'Invalid character in domain' => ['user@domain!.com'],
            'Missing top-level domain' => ['user@domain'],
            'Space in local part' => ['user name@domain.com'],
            'Space in domain' => ['user@domain .com'],
            'Double dot in domain' => ['user@domain..com'],
            'Invalid character' => ['user@-domain.com'],
            'Invalid domain (example.invalid)' => ['test@example.invalid'],
            'Invalid top level domain (example.web)' => ['test@example.web'],
            'Trailing space' => ['test@example '],
            'Leading space' => [' test@example.com'],
        ];
    }
}
