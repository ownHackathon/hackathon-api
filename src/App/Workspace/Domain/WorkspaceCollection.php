<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Domain;

use InvalidArgumentException;
use ownHackathon\Core\SharedKernel\Utils\Collection;

use function get_debug_type;
use function sprintf;

final class WorkspaceCollection extends Collection implements WorkspaceCollectionInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof WorkspaceInterface)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be an instance of %s',
                    get_debug_type($value),
                    WorkspaceInterface::class,
                ),
            );
        }
        parent::offsetSet($offset, $value);
    }
}
