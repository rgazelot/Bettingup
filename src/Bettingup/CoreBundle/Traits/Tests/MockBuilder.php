<?php

namespace Bettingup\CoreBundle\Traits\Tests;

trait MockBuilder
{
    public function getMockWithoutConstructor($fqcn)
    {
        $args = func_get_args();
        $fqcn = array_shift($args);

        $builder = $this->getMockBuilder($fqcn);

        if (count($args) > 0) {
            $builder->setMethods($args);
        }

        return $builder->disableOriginalConstructor()->getMock();
    }

    abstract public function getMockBuilder($entity);
}
