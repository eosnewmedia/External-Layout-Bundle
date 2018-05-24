<?php

namespace Enm\Bundle\ExternalLayoutBundle;

use Enm\Bundle\ExternalLayoutBundle\DependencyInjection\Compiler\FinisherPass;
use Enm\Bundle\ExternalLayoutBundle\DependencyInjection\Compiler\ManipulatorPass;
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
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new ManipulatorPass());
        $container->addCompilerPass(new FinisherPass());
    }
}
