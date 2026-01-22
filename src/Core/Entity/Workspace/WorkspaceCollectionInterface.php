<?php declare(strict_types=1);

namespace Core\Entity\Workspace;

use App\Entity\Workspace\Workspace;
use Core\Utils\CollectionInterface;

/**
 * @method Workspace offsetGet(mixed $offset)
 * @method Workspace current()
 * @method Workspace first()
 * @method Workspace last()
 */
interface WorkspaceCollectionInterface extends CollectionInterface
{
}
