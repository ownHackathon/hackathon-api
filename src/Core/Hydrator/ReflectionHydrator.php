<?php declare(strict_types=1);

namespace Core\Hydrator;

use ReflectionClass;

use function is_object;

class ReflectionHydrator extends \Laminas\Hydrator\ReflectionHydrator
{
    public function hydrateList(array $values, string $className): array
    {
        $hydratedList = [];

        foreach ($values as $data) {
            if ($data) {
                $hydratedList[] = $this->hydrate($data, $className);
            }
        }

        return $hydratedList;
    }

    public function hydrate(bool|array $data, string|object $object): ?object
    {
        if (!$data) {
            return null;
        }

        if (!is_object($object)) {
            $object = new ReflectionClass($object);
            $object = $object->newInstanceWithoutConstructor();
        }

        return parent::hydrate($data, $object);
    }

    public function extractList(array $data): array
    {
        $extractedList = [];

        foreach ($data as $value) {
            if (is_object($value)) {
                $extractedList[] = $this->extract($value);
            }
        }
        return $extractedList;
    }
}
