<?php

namespace Gl3n\HttpQueryStringFilterBundle\ParameterType;

/**
 * Contains all parameter types
 */
class ParameterTypeChain
{
    /**
     * @var array
     */
    private $parameterTypes;

    /**
     * Adds a parameter type
     *
     * @param ParameterTypeInterface $parameterType
     * @param string                 $typeName
     */
    public function addParameterType(ParameterTypeInterface $parameterType, $typeName)
    {
        $this->parameterTypes[$typeName] = $parameterType;
    }

    /**
     * Gets a parameter type
     *
     * @param string $typeName
     *
     * @return ParameterTypeInterface
     */
    public function getType($typeName)
    {
        return $this->parameterTypes[$typeName];
    }
}