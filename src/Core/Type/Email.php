<?php declare(strict_types=1);

namespace ownHackathon\Core\Type;

use InvalidArgumentException;
use ValueError;

use function sprintf;

final class Email implements TypeInterface
{
    private string $value;

    public function __construct(self|string $value)
    {
        $this->value = $value instanceof self ? (string)$value : $this->prepareValue($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function serialize(): string
    {
        return $this->toString();
    }

    public function __serialize(): array
    {
        return ['string' => $this->toString()];
    }

    public function unserialize(string $data): void
    {
        $this->__construct($data);
    }

    public function __unserialize(array $data): void
    {
        // @codeCoverageIgnoreStart
        if (!isset($data['string'])) {
            throw new ValueError(sprintf('%s(): Argument #1 ($data) is invalid', __METHOD__));
        }
        // @codeCoverageIgnoreEnd

        $this->unserialize($data['string']);
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }

    private function prepareValue(string $value): string
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                'Value must be an e-mail'
            );
        }

        return $value;
    }
}
