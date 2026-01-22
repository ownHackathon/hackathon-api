<?php declare(strict_types=1);

namespace App\Entity\Workspace;

use Core\Entity\Account\AccountInterface;
use Core\Entity\Workspace\WorkspaceCollectionInterface;
use Core\Entity\Workspace\WorkspaceInterface;
use Core\Utils\Collection;
use InvalidArgumentException;

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
                    AccountInterface::class
                )
            );
        }
        parent::offsetSet($offset, $value);
    }
}
