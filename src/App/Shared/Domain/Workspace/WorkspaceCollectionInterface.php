<?php declare(strict_types=1);

namespace ownHackathon\Shared\Domain\Workspace;

use Exdrals\Core\Shared\Utils\CollectionInterface;

/**
 * @method WorkspaceInterface offsetGet(mixed $offset)
 * @method WorkspaceInterface current()
 * @method WorkspaceInterface first()
 * @method WorkspaceInterface last()
 */
interface WorkspaceCollectionInterface extends CollectionInterface
{
}
