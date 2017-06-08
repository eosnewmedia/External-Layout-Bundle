<?php
namespace Enm\Bundle\ExternalLayoutBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class EnmExternalLayoutExtension extends ConfigurableExtension
{
    /**
     * Configures the passed container according to the merged configuration.
     *
     * @param array $mergedConfig
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $container->setParameter(
          'enm.external_layout.layouts',
          $mergedConfig['layouts']
        );
        
        $loader = new XmlFileLoader(
          $container,
          new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');
    }
}
