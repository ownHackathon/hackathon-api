<?php declare(strict_types=1);

namespace App\Account\Identity\Domain;

use Core\SharedKernel\Utils\CollectionInterface;

/**
 * @method AccountActivation offsetGet(mixed $offset)
 * @method AccountActivation current()
 * @method AccountActivation first()
 * @method AccountActivation last()
 */
interface AccountActivationCollectionInterface extends CollectionInterface
{
}
