<?php

namespace App\Container;

use ReflectionClass;
use Closure;
use Exception;

class Container
{
    private array $bindings = [];
    private array $instances = [];
    private array $resolving = [];

    public function get(string $abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (isset($this->resolving[$abstract])) {
            throw new Exception(
                "Circular dependency detected: $abstract"
            );
        }

        $this->resolving[$abstract] = true;

        if (isset($this->bindings[$abstract])) {

            $concrete = $this->bindings[$abstract];

            if ($concrete instanceof Closure) {
                $instance = $concrete();

                $this->instances[$abstract] = $instance;

                unset($this->resolving[$abstract]);

                return $instance;
            }

            $instance = $this->build($concrete);

            $this->instances[$abstract] = $instance;

            unset($this->resolving[$abstract]);

            return $instance;
        }

        $instance = $this->build($abstract);

        $this->instances[$abstract] = $instance;

        unset($this->resolving[$abstract]);

        return $instance;
    }

    private function build(string $class)
    {
        $reflection = new ReflectionClass($class);

        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $class();
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {

            $type = $parameter->getType();

            if ($type->isBuiltin()) {
                throw new Exception(
                    "Cannot automatically resolve built-in type: "
                        . $type->getName()
                );
            }

            $dependencyClass = $type->getName();

            $dependencies[] = $this->get($dependencyClass);
        }

        return new $class(...$dependencies);
    }

    public function bind(string $abstract, $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }
}
