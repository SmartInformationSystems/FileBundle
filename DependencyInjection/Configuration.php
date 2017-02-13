<?php
namespace SmartInformationSystems\FileBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('smart_information_systems_file');

        $rootNode->children()
            ->arrayNode('storage')->children()
                ->enumNode('type')->values(array('dummy', 'filesystem'))->isRequired()->end()
                ->arrayNode('params')->children()
                    ->scalarNode('path')->end()
                    ->scalarNode('url')->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
