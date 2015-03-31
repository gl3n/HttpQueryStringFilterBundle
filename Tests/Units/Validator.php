<?php

namespace Gl3n\HttpQueryStringFilterBundle\Tests\Units;

use mageekguy\atoum;
use Symfony\Component\HttpFoundation\ParameterBag;
use Gl3n\SerializationGroupBundle;
use Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeChain;

/**
 * Tests on Validator
 */
class Validator extends atoum\test
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
     * Tests "validate" method : value must be an array
     */
    public function test_validate_valueMustBeAnArray()
    {
        $validator = new \Gl3n\HttpQueryStringFilterBundle\Validator($this->_getParameterTypeChainMock());
        $this
            ->exception(
                function() use ($validator) {
                    $validator->validate(
                        'ids',
                        ['type' => 'special', 'array' => true],
                        '1'
                    );
                }
            )
            ->isInstanceOf('\InvalidArgumentException');
    }

    /**
     * Tests "validate" method with a valid array value and a defined reg exp
     */
    public function test_validate_array_definedRegExp_valid()
    {
        $validator = new \Gl3n\HttpQueryStringFilterBundle\Validator($this->_getParameterTypeChainMock());
        $validator->validate(
            'ids',
            ['type' => 'special', 'array' => true, 'reg_exp' => '^\d$'],
            ['1', '2', '3']
        );

        $this->boolean(true)->isTrue();
    }

    /**
     * Tests "validate" method with a valid array value and a default reg exp
     */
    public function test_validate_array_defaultRegExp_valid()
    {
        $validator = new \Gl3n\HttpQueryStringFilterBundle\Validator($this->_getParameterTypeChainMock('^[a-c]$'));
        $validator->validate(
            'ids',
            ['type' => 'special', 'array' => true],
            ['a', 'b', 'c']
        );

        $this->boolean(true)->isTrue();
    }

    /**
     * Tests "validate" method with an invalid array value and a default reg exp
     */
    public function test_validate_array_defaultRegExp_invalid()
    {
        $validator = new \Gl3n\HttpQueryStringFilterBundle\Validator($this->_getParameterTypeChainMock('^[a-c]$'));
        $this
            ->exception(
                function() use ($validator) {
                    $validator->validate(
                        'ids',
                        ['type' => 'special', 'array' => true],
                        ['a', 'b', 'c', 'd']
                    );
                }
            )
            ->isInstanceOf('\InvalidArgumentException');
    }

    /**
     * Tests "validate" method with a valid string value and a defined reg exp
     */
    public function test_validate_string_definedRegExp_valid()
    {
        $validator = new \Gl3n\HttpQueryStringFilterBundle\Validator($this->_getParameterTypeChainMock());
        $validator->validate(
            'id',
            ['type' => 'special', 'array' => false, 'reg_exp' => '^\d$'],
            '2'
        );

        $this->boolean(true)->isTrue();
    }

    /**
     * Tests "validate" method with an invalid string and a defined reg exp
     */
    public function test_validate_string_definedRegExp_invalid()
    {
        $validator = new \Gl3n\HttpQueryStringFilterBundle\Validator($this->_getParameterTypeChainMock());
        $this
            ->exception(
                function() use ($validator) {
                    $validator->validate(
                        'id',
                        ['type' => 'special', 'array' => false, 'reg_exp' => '^\d$'],
                        '20'
                    );
                }
            )
            ->isInstanceOf('\InvalidArgumentException');
    }

    /**
     * Tests "checkMissingParameter" with a missing parameter
     */
    public function test_checkMissingParameter_error()
    {
        $parameterBag = new ParameterBag(['one' => 1]);
        $this
            ->exception(
                function() use ($parameterBag) {
                    \Gl3n\HttpQueryStringFilterBundle\Validator::checkMissingParameter('two', ['required' => true], $parameterBag);
                }
            )
            ->isInstanceOf('\InvalidArgumentException');
    }

    /**
     * Tests "checkMissingParameter" without missing parameter
     */
    public function test_checkMissingParameter_success()
    {
        $parameterBag = new ParameterBag(['one' => 1, 'two' => 2]);
        \Gl3n\HttpQueryStringFilterBundle\Validator::checkMissingParameter('two', ['required' => true], $parameterBag);

        $this->boolean(true)->isTrue();
    }

    /**
     * Returns ParameterTypeChain mock
     *
     * @param null|string $defaultRegExp
     *
     * @return ParameterTypeChain
     */
    private function _getParameterTypeChainMock($defaultRegExp = null)
    {
        $parameterType = $this->prophet->prophesize('Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeInterface');
        if (!is_null($defaultRegExp)) {
            $parameterType->getDefaultRegExp()->shouldBeCalled()->willReturn($defaultRegExp);
        }

        $parameterTypeChain = $this->prophet->prophesize('Gl3n\HttpQueryStringFilterBundle\ParameterType\ParameterTypeChain');
        $parameterTypeChain->getParameterType('special')->willReturn($parameterType->reveal());

        return $parameterTypeChain->reveal();
    }
}