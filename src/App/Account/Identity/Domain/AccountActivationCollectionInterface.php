<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain;

use ownHackathon\Core\SharedKernel\Utils\CollectionInterface;

/**
 * @method AccountActivation offsetGet(mixed $offset)
 * @method AccountActivation current()
 * @method AccountActivation first()
 * @method AccountActivation last()
 */
interface AccountActivationCollectionInterface extends CollectionInterface
{
}
