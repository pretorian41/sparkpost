<?php

namespace Braem\SparkpostMailerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('braem_sparkpost_mailer');
        $rootNode
            ->children()
            ->scalarNode('api_key')->end()
            ->scalarNode('api_uri')->end()
            ->scalarNode('caller')->end()
            ->scalarNode('from_email')->end()
            ->scalarNode('company_name')->end()
            ->scalarNode('email_for_test_env')->end()
            ->scalarNode('environment')->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
