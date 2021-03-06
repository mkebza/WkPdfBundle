<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\WkPDF\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('m_kebza_wk_pdf');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('bin')->defaultValue('/usr/local/bin/wkhtmltopdf')->end()
                ->scalarNode('tmp')->defaultValue('/tmp/')->end()
                ->scalarNode('default_profile')->defaultValue('a4')->end()
                ->arrayNode('profiles')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->scalarPrototype()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
