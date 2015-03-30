<?php

namespace Gl3n\HttpQueryStringFilterBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class Gl3nHttpQueryStringFilterExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Resolve filters parameters inclusions
        $filters = $config['filters'];
        foreach (array_keys($filters) as $filterName) {
            $filters[$filterName]['params'] = self::_resolveIncludedParameters($filters, $filterName);
        }
        $filters = array_map(function($v) {
            return $v['params'];
        }, $filters);

        $container->setParameter('gl3n_http_query_string_filter.config.filters', $filters);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }

    /**
     * Resolves parameters inclusions
     *
     * @param array  $filters
     * @param string $filterName
     *
     * @return array
     */
    private static function _resolveIncludedParameters($filters, $filterName)
    {
        foreach ($filters[$filterName]['include'] as $includedFilter) {
            $filters[$filterName]['params'] = array_merge(
                $filters[$filterName]['params'],
                self::_resolveIncludedParameters($filters, $includedFilter)
            );
        }

        return $filters[$filterName]['params'];
    }
}