<?php
namespace Enm\Bundle\ExternalLayoutBundle;

use Enm\Bundle\ExternalLayoutBundle\DependencyInjection\Compiler\BlockBuilderPass;
use Enm\Bundle\ExternalLayoutBundle\DependencyInjection\Compiler\SourceLoaderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class EnmExternalLayoutBundle extends Bundle
{
    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     *
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new SourceLoaderPass());
        $container->addCompilerPass(new BlockBuilderPass());
    }
}
