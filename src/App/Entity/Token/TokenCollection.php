<?php declare(strict_types=1);

namespace App\Entity\Token;

use InvalidArgumentException;
use Core\Entity\Token\TokenCollectionInterface;
use Core\Entity\Token\TokenInterface;
use Core\Utils\Collection;

use function get_class;
use function sprintf;

class TokenCollection extends Collection implements TokenCollectionInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof TokenInterface)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be an instance of %s',
                    get_class($value),
                    TokenInterface::class
                )
            );
        }
        parent::offsetSet($offset, $value);
    }
}
