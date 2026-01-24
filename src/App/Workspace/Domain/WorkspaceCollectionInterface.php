<?php declare(strict_types=1);

namespace App\Workspace\Domain;

use Shared\Utils\CollectionInterface;

/**
 * @method Workspace offsetGet(mixed $offset)
 * @method Workspace current()
 * @method Workspace first()
 * @method Workspace last()
 */
interface WorkspaceCollectionInterface extends CollectionInterface
{
}
