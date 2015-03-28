<?php

namespace Gl3n\HttpQueryStringFilterBundle\ParameterType;

/**
 * Boolean parameter type
 */
class BooleanParameterType implements ParameterTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function castValue($value)
    {
        return 'true' === $value ? true : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultRegExp()
    {
        return '^true|false$';
    }
}