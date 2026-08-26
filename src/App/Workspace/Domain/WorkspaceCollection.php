<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Domain;

use ownHackathon\Core\Shared\Utils\Collection;
use InvalidArgumentException;
use ownHackathon\App\Shared\Domain\Workspace\WorkspaceCollectionInterface;
use ownHackathon\App\Shared\Domain\Workspace\WorkspaceInterface;

use function get_class;
use function sprintf;

class WorkspaceCollection extends Collection implements WorkspaceCollectionInterface
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
                    get_class($value),
                    WorkspaceInterface::class
                )
            );
        }
        parent::offsetSet($offset, $value);
    }
}
