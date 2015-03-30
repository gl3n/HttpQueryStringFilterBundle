<?php

namespace Gl3n\HttpQueryStringFilterBundle;

use Symfony\Component\HttpFoundation\ParameterBag;
use Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeChain;

/**
 * Validates query parameter
 */
class Validator
{
    /**
     * @var ParameterTypeChain
     */
    private $parameterTypeChain;

    /**
     * Constructor
     *
     * @param ParameterTypeChain $parameterTypeChain
     */
    public function __construct(ParameterTypeChain $parameterTypeChain)
    {
        $this->parameterTypeChain = $parameterTypeChain;
    }

    /**
     * Validates a parameter
     *
     * @param string       $name
     * @param array        $options
     * @param string|array $value
     */
    public function validate($name, array $options, $value)
    {
        if ($options['array'] && !is_array($value)) {
            throw new \InvalidArgumentException(sprintf('Parameter "%s" must be an array', $name));
        }

        foreach ((array) $value as $itemValue) {
            $regExp = isset($options['reg_exp']) ? $options['reg_exp'] : $this->parameterTypeChain->getParameterType($options['type'])->getDefaultRegExp();

            if (1 !== preg_match(sprintf('/%s/', $regExp), $itemValue)) {
                throw new \InvalidArgumentException(sprintf('Invalid value for parameter "%s"', $name));
            }
        }
    }

    /**
     * Checks for missing parameter
     *
     * @param string       $name
     * @param array        $options
     * @param ParameterBag $bag
     */
    public static function checkMissingParameter($name, array $options, ParameterBag $bag)
    {
        if ($options['required'] && !$bag->has($name)) {
            throw new \InvalidArgumentException(sprintf('Missing required parameter "%s"', $name));
        }
    }
}