<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Handler;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use ownHackathon\App\DTO\AccessToken;
use ownHackathon\App\DTO\RefreshToken;
use ownHackathon\App\Handler\Account\AuthenticationHandler;
use ownHackathon\UnitTest\Mock\Constants\Token;

use function json_decode;
use function property_exists;

use const JSON_THROW_ON_ERROR;

class AuthenticationHandlerTest extends AbstractTestHandler
{
    public function testResponse(): void
    {
        $request = $this->request->withAttribute(AccessToken::class, AccessToken::fromString(Token::ACCESS_TOKEN_VALID))
            ->withAttribute(RefreshToken::class, RefreshToken::fromString(Token::REFRESH_TOKEN_VALID));

        $authenticationHandler = new AuthenticationHandler();

        $response = $authenticationHandler->handle($request);

        $json = json_decode((string)$response->getBody(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(HTTP::STATUS_OK, $response->getStatusCode());
        $this->assertTrue(property_exists($json, 'accessToken') && $json->accessToken === Token::ACCESS_TOKEN_VALID);
        $this->assertTrue(property_exists($json, 'refreshToken') && $json->refreshToken === Token::REFRESH_TOKEN_VALID);
    }
}
