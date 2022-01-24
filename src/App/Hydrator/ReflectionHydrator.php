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

    public function hydrateList(array $values, string $className): array
    {
        $hydratedList = [];

        foreach ($values as $data) {
            if ($data) {
                $hydratedList[] = $this->hydrate($data, new $className());
            }
        }

        return $hydratedList;
    }
}
