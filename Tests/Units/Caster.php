<?php

namespace Gl3n\HttpQueryStringFilterBundle\Tests\Units;

use mageekguy\atoum;
use Gl3n\HttpQueryStringFilterBundle;

/**
 * Tests on Caster
 */
class Caster extends atoum\test
{
    /**
     * @var \Prophecy\Prophet
     */
    private $prophet;

    /**
     * {@inheritdoc}
     */
    public function beforeTestMethod($testMethod)
    {
        $this->prophet = new \Prophecy\Prophet;
    }

    /**
     * {@inheritdoc}
     */
    public function afterTestMethod($testMethod)
    {
        $this->prophet->checkPredictions();
    }

    /**
     * Tests "cast" method with a string value
     */
    public function test_cast_string()
    {
        $parameterType = $this->prophet->prophesize('Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeInterface');
        $parameterType->castValue('one')->shouldBeCalled()->willReturn(1);

        $parameterTypeChain = $this->prophet->prophesize('Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeChain');
        $parameterTypeChain->getParameterType('special')->willReturn($parameterType->reveal());

        $caster = new HttpQueryStringFilterBundle\Caster($parameterTypeChain->reveal());
        $result = $caster->cast(['type' => 'special', 'array' => false], 'one');

        $this->integer($result)->isEqualTo(1);
    }

    /**
     * Tests "cast" method with an array value
     */
    public function test_cast_array()
    {
        $parameterType = $this->prophet->prophesize('Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeInterface');
        $parameterType->castValue('one')->shouldBeCalled()->willReturn(1);
        $parameterType->castValue('two')->shouldBeCalled()->willReturn(2);
        $parameterType->castValue('three')->shouldBeCalled()->willReturn(3);

        $parameterTypeChain = $this->prophet->prophesize('Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeChain');
        $parameterTypeChain->getParameterType('special')->willReturn($parameterType->reveal());

        $caster = new HttpQueryStringFilterBundle\Caster($parameterTypeChain->reveal());
        $result = $caster->cast(
            ['type' => 'special', 'array' => true],
            ['one', 'two', 'three']
        );

        $this->array($result)->isEqualTo([1, 2, 3]);
    }

    /**
     * Tests "getDefaultValue" with a string as default value
     */
    public function test_getDefaultValue_string()
    {
        $parameterType = $this->prophet->prophesize('Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeInterface');
        $parameterType->castValue('default_value')->shouldBeCalled()->willReturn('casted_default_value');

        $parameterTypeChain = $this->prophet->prophesize('Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeChain');
        $parameterTypeChain->getParameterType('special')->willReturn($parameterType->reveal());

        $caster = new HttpQueryStringFilterBundle\Caster($parameterTypeChain->reveal());
        $result = $caster->getDefaultValue([
            'type' => 'special',
            'array' => false,
            'default' => 'default_value'
        ]);

        $this->string($result)->isEqualTo('casted_default_value');
    }

    /**
     * Tests "getDefaultValue" with an empty array as default value
     */
    public function test_getDefaultValue_emptyArray()
    {
        $parameterType = $this->prophet->prophesize('Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeInterface');
        $parameterTypeChain = $this->prophet->prophesize('Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeChain');
        $parameterTypeChain->getParameterType('special')->willReturn($parameterType->reveal());

        $caster = new HttpQueryStringFilterBundle\Caster($parameterTypeChain->reveal());
        $result = $caster->getDefaultValue([
            'type' => 'special',
            'array' => true,
            'default' => '[]'
        ]);

        $this->array($result)->isEqualTo([]);
    }

    /**
     * Tests "getDefaultValue" with an empty array as default value
     */
    public function test_getDefaultValue_filledArray()
    {
        $parameterType = $this->prophet->prophesize('Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeInterface');
        $parameterType->castValue('one')->shouldBeCalled()->willReturn(1);
        $parameterType->castValue('two')->shouldBeCalled()->willReturn(2);
        $parameterType->castValue('three')->shouldBeCalled()->willReturn(3);

        $parameterTypeChain = $this->prophet->prophesize('Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeChain');
        $parameterTypeChain->getParameterType('special')->willReturn($parameterType->reveal());

        $caster = new HttpQueryStringFilterBundle\Caster($parameterTypeChain->reveal());
        $result = $caster->getDefaultValue([
            'type' => 'special',
            'array' => true,
            'default' => '["one", "two", "three"]'
        ]);

        $this->array($result)->isEqualTo([1, 2, 3]);
    }
}