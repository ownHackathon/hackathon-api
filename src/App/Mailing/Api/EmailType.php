<?php declare(strict_types=1);

namespace App\Mailing\Api;

use App\Mailing\Api\Exception\InvalidArgumentException;
use Core\SharedKernel\Type\TypeInterface;
use Override;

use function filter_var;
use function sprintf;

use const FILTER_VALIDATE_EMAIL;

final class EmailType implements TypeInterface
{
    private string $value;

    public function __construct(self|string $value)
    {
        $this->value = $value instanceof self ? (string)$value : $this->prepareValue($value);
    }

    public static function fromString(string $value): EmailType
    {
        return new self($value);
    }

    #[Override]
    public function toString(): string
    {
        return $this->value;
    }

    #[Override]
    public function serialize(): string
    {
        return $this->toString();
    }

    #[Override]
    public function unserialize(string $data): void
    {
        $this->value = $this->prepareValue($data);
    }

    #[Override]
    public function jsonSerialize(): string
    {
        return $this->toString();
    }

    private function prepareValue(string $value): string
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                sprintf('Value must be a valid email address: %s', $value),
            );
        }

        return $value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function __serialize(): array
    {
        return ['string' => $this->toString()];
    }

    public function __unserialize(array $data): void
    {
        // @codeCoverageIgnoreStart
        if (!isset($data['string'])) {
            throw new InvalidArgumentException(sprintf('%s(): Argument #1 ($data) is invalid', __METHOD__));
        }
        // @codeCoverageIgnoreEnd

        $this->unserialize($data['string']);
    }
}
