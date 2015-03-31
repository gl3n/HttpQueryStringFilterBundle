<?php

namespace Gl3n\HttpQueryStringFilterBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds tagged parameter type services to ParameterTypeChain
 */
class ParameterTypeCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition('gl3n_http_query_string_filter.parameter_type_chain');
        $taggedServices = $container->findTaggedServiceIds('gl3n_http_query_string_filter.parameter_type');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'addParameterType',
                    array(new Reference($id), $attributes['type'])
                );
            }
        }
    }
}
