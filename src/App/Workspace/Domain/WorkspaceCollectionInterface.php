<?php declare(strict_types=1);

namespace App\Workspace\Domain;

use Core\SharedKernel\Utils\CollectionInterface;

/**
 * @method WorkspaceInterface offsetGet(mixed $offset)
 * @method WorkspaceInterface current()
 * @method WorkspaceInterface first()
 * @method WorkspaceInterface last()
 */
interface WorkspaceCollectionInterface extends CollectionInterface
{
}
