<?php

namespace Utc\CasBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('utc_cas');
        $rootNode
            ->children()
                ->scalarNode('base_url')
                    ->defaultValue('https://cas.utc.fr/cas/')
                ->end()
                ->scalarNode('login_path')
                    ->defaultValue('login')
                ->end()
                ->scalarNode('logout_path')
                    ->defaultValue('logout')
                ->end()
                ->scalarNode('service_validate_path')
                    ->defaultValue('serviceValidate')
                ->end()
                ->scalarNode('ticket_field')
                    ->defaultValue('ticket')
                ->end()
                ->scalarNode('service_field')
                    ->defaultValue('service')
                ->end()
                ->scalarNode('success_path')
                    ->isRequired()
                ->end()
            ->end()
        ;



        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
