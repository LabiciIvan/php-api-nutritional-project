<?php

declare(strict_types=1);

namespace App\Utilities;

use ReflectionClass;

class DependencyInjector extends ReflectionClass
{
    private string $classMethod;

    private string $className;

    private array $dependencies = [];

    public function __construct(string $class, string $method)
    {
        $this->className = $class;

        $this->classMethod = $method;

        parent::__construct($class);
    }

    public function hasDependencies(): ?array
    {
        try {
            $method =  $this->getMethod($this->classMethod);
        } catch (\ReflectionException $e) {
            ErrorLogger::logError('Missing method call in ' . __METHOD__ . ' - ' . $e->getMessage() . ' for class::'. $this->className, __DIR__ . '/../../errors.txt');
            return null;
        }

        $methodParameters = $method->getParameters();

        foreach ($methodParameters as $parameter) {
            $parameterType = $parameter->getType();

            $parameterClass = $parameterType->getName();

            if (class_exists($parameterClass)) {
                $this->dependencies[] = $parameterClass;
            }
        }

        return $this->dependencies;
    }
}
