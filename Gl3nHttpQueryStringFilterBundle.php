<?php

namespace Gl3n\HttpQueryStringFilterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Gl3n\HttpQueryStringFilterBundle\DependencyInjection\Compiler\ParameterTypeCompilerPass;

/**
 * Gl3nHttpQueryStringFilterBundle
 */
class Gl3nHttpQueryStringFilterBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ParameterTypeCompilerPass);
    }
}
