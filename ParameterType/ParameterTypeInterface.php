<?php

namespace Gl3n\HttpQueryStringFilterBundle\ParameterType;

/**
 * Parameter types must implement this interface
 */
interface ParameterTypeInterface
{
    /**
     * Casts value
     *
     * @param string $value
     *
     * @return mixed
     */
    public function castValue($value);

    /**
     * Returns default regular expression pattern
     *
     * @return string
     */
    public function getDefaultRegExp();
}