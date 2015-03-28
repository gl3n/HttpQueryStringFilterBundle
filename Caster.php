<?php

namespace Gl3n\HttpQueryStringFilterBundle;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeChain;

/**
 * Casts given or default query parameter
 */
class Caster
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
     * Casts a value
     *
     * @param array        $options
     * @param string|array $value
     */
    public function cast(array $options, $value)
    {
        $parameterType = $this->parameterTypeChain->getParameterType($options['type']);

        if ($options['array']) {
            foreach ($value as &$subValue) {
                $subValue = $parameterType->castValue($subValue);
            }
        } else {
            $value = $parameterType->castValue($value);
        }

        return $value;
    }

    /**
     * Returns casted default value
     *
     * @param array $options
     *
     * @return mixed
     */
    public function getDefaultValue(array $options)
    {
        $parameterType = $this->parameterTypeChain->getParameterType($options['type']);

        if ($options['array']) {
            $language = new ExpressionLanguage;
            $defaultValue = $language->evaluate($options['default']);
            if (is_array($defaultValue)) {
                foreach ($defaultValue as &$subValue) {
                    $subValue = $parameterType->castValue($subValue);
                }
            }

            return $defaultValue;
        } else {
            return $parameterType->castValue($options['default']);
        }
    }
}