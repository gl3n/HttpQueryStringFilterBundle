<?php

namespace Gl3n\HttpQueryStringFilterBundle\ParameterType;

/**
 * String parameter type
 */
class StringParameterType implements ParameterTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function castValue($value)
    {
        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultRegExp()
    {
        return '^.+$';
    }
}