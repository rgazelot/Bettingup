<?php

namespace Bettingup\CoreBundle\Traits\Tests;

use \ReflectionClass,
    \ReflectionObject,
    \ReflectionMethod,
    \ReflectionProperty;

trait ReflectionUtils
{
    public function setProperty($entity, $property, $value)
    {
        $refl = $this->getAccessibleProperty($entity, $property);
        $refl->setValue($entity, $value);

        return $entity;
    }

    private function getAccessibleMethod($fqcn, $method)
    {
        $method = new ReflectionMethod($fqcn, $method);
        $method->setAccessible(true);

        return $method;
    }

    private function getAccessibleProperty($fqcn, $property)
    {
        $refl = new ReflectionProperty($fqcn, $property);
        $refl->setAccessible(true);

        return $refl;
    }

    private function getAccessibleProperties($fqcn)
    {
        $refl = is_object($fqcn) ? new ReflectionObject($fqcn) : new ReflectionClass($fqcn);
        $properties = [];

        foreach ($refl->getProperties() as $property) {
            $property->setAccessible(true);

            $properties[$property->name] = $property;
        }

        return $properties;
    }
}

