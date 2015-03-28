<?php

namespace Gl3n\HttpQueryStringFilterBundle\ParameterType;

/**
 * Integer parameter type
 */
class IntegerParameterType implements ParameterTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function castValue($value)
    {
        return (int) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultRegExp()
    {
        return '^\d+$';
    }
}