<?php
namespace Enm\Bundle\ExternalLayoutBundle\SourceLoader;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class SourceLoaderRegistry
{
    /**
     * @var array
     */
    private $sourceLoaders = [];
    
    /**
     * @param string $layout
     * @param SourceLoaderInterface $loader
     *
     * @return $this
     */
    public function addSourceLoader($layout, SourceLoaderInterface $loader)
    {
        $this->sourceLoaders[$layout] = $loader;
        
        return $this;
    }
    
    /**
     * @param $layout
     *
     * @return SourceLoaderInterface
     */
    public function getSourceLoader($layout)
    {
        if (array_key_exists($layout, $this->sourceLoaders)) {
            return $this->sourceLoaders[$layout];
        }
        
        $newLoader                    = new SourceLoader();
        $this->sourceLoaders[$layout] = $newLoader;
        
        return $newLoader;
    }
}
