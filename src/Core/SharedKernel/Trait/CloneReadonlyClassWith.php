<?php declare(strict_types=1);

namespace Core\SharedKernel\Trait;

/**
 * @see https://github.com/spatie/php-cloneable/
 */
trait CloneReadonlyClassWith
{
    protected function with(mixed ...$args): self
    {
        $constructor = new \ReflectionMethod($this, '__construct');
        $parameters = [];

        foreach ($constructor->getParameters() as $parameter) {
            $name = $parameter->getName();
            $parameters[$name] = array_key_exists($name, $args)
                ? $args[$name]
                // @phpstan-ignore property.dynamicName
                : $this->{$name};
        }

        return new self(...$parameters);
    }
}
