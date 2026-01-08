<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

use function array_key_exists;

class MockServerRequest implements ServerRequestInterface
{
    public function __construct(
        private array $attributes = [],
        private array $headers = [],
        private array $body = [],
        private array $queryParams = [],
    ) {
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return array_key_exists($name, $this->headers);
    }

    public function getHeader($name): array
    {
        return array_key_exists($name, $this->headers) ? $this->headers[$name] : [];
    }

    public function getHeaderLine($name): string
    {
        return $this->headers[$name] ?? '';
    }

    public function withHeader($name, $value): MessageInterface
    {
        $header = clone $this;
        $header->headers[$name] = $value;

        return $header;
    }

    /**
     * @return StreamInterface|array
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): self
    {
        $queryParams = clone $this;

        $queryParams->queryParams = $query;

        return $queryParams;
    }

    public function getParsedBody(): object|array|null
    {
        return $this->body;
    }

    public function withParsedBody($data): ServerRequestInterface
    {
        $body = clone $this;
        $body->body = $data;

        return $body;
    }

    public function getAttribute($name, $default = null)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        return $default;
    }

    public function withAttribute($name, $value): MockServerRequest
    {
        $attributes = clone $this;
        $attributes->attributes[$name] = $value;

        return $attributes;
    }

    public function getProtocolVersion(): string
    {
        // TODO: Implement getProtocolVersion() method.
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        // TODO: Implement withProtocolVersion() method.
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        // TODO: Implement withAddedHeader() method.
    }

    public function withoutHeader(string $name): MessageInterface
    {
        // TODO: Implement withoutHeader() method.
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        // TODO: Implement withBody() method.
    }

    public function getRequestTarget(): string
    {
        // TODO: Implement getRequestTarget() method.
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        // TODO: Implement withRequestTarget() method.
    }

    public function getMethod(): string
    {
        // TODO: Implement getMethod() method.
    }

    public function withMethod(string $method): RequestInterface
    {
        // TODO: Implement withMethod() method.
    }

    public function getUri(): UriInterface
    {
        return new \Laminas\Diactoros\Uri('http://example.com/');
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        // TODO: Implement withUri() method.
    }

    public function getServerParams(): array
    {
        return [];
    }

    public function getCookieParams(): array
    {
        // TODO: Implement getCookieParams() method.
    }

    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        // TODO: Implement withCookieParams() method.
    }

    public function getUploadedFiles(): array
    {
        // TODO: Implement getUploadedFiles() method.
    }

    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        // TODO: Implement withUploadedFiles() method.
    }

    public function getAttributes(): array
    {
        // TODO: Implement getAttributes() method.
    }

    public function withoutAttribute(string $name): ServerRequestInterface
    {
        // TODO: Implement withoutAttribute() method.
    }
}
