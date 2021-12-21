<?php declare(strict_types=1);

namespace App\Hydrator;

class ReflectionHydrator extends \Laminas\Hydrator\ReflectionHydrator
{
    public function hydrate(bool|array $data, object $object): ?object
    {
        if (!$data) {
            return null;
        }

        return parent::hydrate($data, $object);
    }
}
