<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock;

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

    public function getProtocolVersion()
    {
        // TODO: Implement getProtocolVersion() method.
    }

    public function withProtocolVersion($version)
    {
        // TODO: Implement withProtocolVersion() method.
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function hasHeader($name)
    {
        return array_key_exists($name, $this->headers);
    }

    public function getHeader($name)
    {
        return array_key_exists($name, $this->headers) ? $this->headers[$name] : null;
    }

    public function getHeaderLine($name)
    {
        return $this->headers[$name] ?? '';
    }

    public function withHeader($name, $value)
    {
        $header = clone $this;
        $header->headers[$name] = $value;

        return $header;
    }

    public function withAddedHeader($name, $value)
    {
        // TODO: Implement withAddedHeader() method.
    }

    public function withoutHeader($name)
    {
        // TODO: Implement withoutHeader() method.
    }

    public function getBody()
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body)
    {
        // TODO: Implement withBody() method.
    }

    public function getRequestTarget()
    {
        // TODO: Implement getRequestTarget() method.
    }

    public function withRequestTarget($requestTarget)
    {
        // TODO: Implement withRequestTarget() method.
    }

    public function getMethod()
    {
        // TODO: Implement getMethod() method.
    }

    public function withMethod($method)
    {
        // TODO: Implement withMethod() method.
    }

    public function getUri()
    {
        // TODO: Implement getUri() method.
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        // TODO: Implement withUri() method.
    }

    public function getServerParams()
    {
        // TODO: Implement getServerParams() method.
    }

    public function getCookieParams()
    {
        // TODO: Implement getCookieParams() method.
    }

    public function withCookieParams(array $cookies)
    {
        // TODO: Implement withCookieParams() method.
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): self
    {
        $queryParams = clone $this;

        $queryParams->queryParams = $query;

        return $queryParams;
    }

    public function getUploadedFiles()
    {
        // TODO: Implement getUploadedFiles() method.
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        // TODO: Implement withUploadedFiles() method.
    }

    public function getParsedBody()
    {
        return $this->body;
    }

    public function withParsedBody($data)
    {
        $body = clone $this;
        $body->body = $data;

        return $body;
    }

    public function getAttributes()
    {
        // TODO: Implement getAttributes() method.
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

    public function withoutAttribute($name)
    {
        // TODO: Implement withoutAttribute() method.
    }
}
