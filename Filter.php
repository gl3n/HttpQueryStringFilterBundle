<?php

namespace Gl3n\HttpQueryStringFilterBundle;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class Filter
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var Caster
     */
    private $caster;

    /**
     * @var array
     */
    private $filters;

    /**
     * Constructor
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param Validator                     $validator
     * @param Caster                        $caster
     * @param array                         $filters
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker, Validator $validator, Caster $caster, array $filters)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->validator = $validator;
        $this->caster = $caster;
        $this->filters = $filters;
    }

    /**
     * Filters query parameter bag
     *
     * @param string       $filterName
     * @param ParameterBag $bag
     *
     * @return ParameterBag
     */
    public function filter($filterName, ParameterBag $bag)
    {
        $expectedParams = $this->filters[$filterName];

        foreach ($bag->all() as $name => $value) {
            if (!isset($expectedParams[$name])) {
                throw new \InvalidArgumentException(sprintf('Unknow parameter "%s"', $name));
            }

            $options = $expectedParams[$name];

            $this->validator->validate($name, $options, $value);
            $bag->set($name, $this->caster->cast($options, $value));
        }

        foreach ($expectedParams as $name => $options) {
            Validator::checkMissingParameter($name, $options, $bag);

            if (!$bag->has($name) && isset($options['default'])) {
                $bag->set($name, $this->caster->getDefaultValue($options));
            }
        }

        return $bag;
    }
}