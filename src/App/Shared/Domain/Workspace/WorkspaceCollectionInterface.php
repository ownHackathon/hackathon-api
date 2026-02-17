<?php declare(strict_types=1);

namespace ownHackathon\Shared\Domain\Workspace;

use Exdrals\Core\Shared\Utils\CollectionInterface;
use ownHackathon\Workspace\Domain\Workspace;

/**
 * @method Workspace offsetGet(mixed $offset)
 * @method Workspace current()
 * @method Workspace first()
 * @method Workspace last()
 */
interface WorkspaceCollectionInterface extends CollectionInterface
{
}
