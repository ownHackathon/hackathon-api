<?php declare(strict_types=1);

namespace App\Service;

trait ConvertArrayToClassArrayTrait
{
    private function convertArrayToClassArray(array $values, string $className): array
    {
        $classArray = [];

        foreach ($values as $data) {
            $classArray[] = $this->hydrator->hydrate($data, new $className());
        }

        return $classArray;
    }
}
