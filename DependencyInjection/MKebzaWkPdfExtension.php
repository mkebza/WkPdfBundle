<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\WkPDF\DependencyInjection;

use MKebza\WkPDF\Service\RenderingProfileRegistry;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class MKebzaWkPdfExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('mkebza_wk_pdf.bin', $config['bin']);
        $container->setParameter('mkebza_wk_pdf.tmp', $config['tmp'] ?? '/tmp/');

        $this->registerProfiles($container, $config['profiles']);
    }

    protected function registerProfiles(ContainerBuilder $container, array $profiles): void
    {
        $registry = $container->getDefinition(RenderingProfileRegistry::class);
        $profiles = array_merge($this->getDefaultProfiles(), $profiles);
        foreach ($profiles as $name => $profile) {
            $registry->addMethodCall('addFromArray', [$name, $profile]);
        }
    }

    protected function getDefaultProfiles(): array
    {
        return [
            'a4' => [
                '--lowquality',
                '--dpi 96',
                '--margin-top 15mm',
                '--margin-left 15mm',
                '--margin-right 15mm',
                '--margin-bottom 15mm',
                "--page-size 'A4'",
                "--encoding 'UTF-8'",
            ],
            'a4_borderless' => [
                '--lowquality',
                '--dpi 96',
                '--margin-top 1mm',
                '--margin-left 1mm',
                '--margin-right 1mm',
                '--margin-bottom 1mm',
                "--page-size 'A4'",
                "--encoding 'UTF-8'",
            ],
        ];
    }
}
