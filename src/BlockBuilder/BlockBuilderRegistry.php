<?php
namespace Enm\Bundle\ExternalLayoutBundle\BlockBuilder;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class BlockBuilderRegistry
{
    /**
     * @var array
     */
    private $blockBuilders = [];
    
    /**
     * @param string $layout
     * @param BlockBuilderInterface $blockBuilder
     *
     * @return $this
     */
    public function addBlockBuilder($layout, BlockBuilderInterface $blockBuilder)
    {
        $this->blockBuilders[$layout] = $blockBuilder;
        
        return $this;
    }
    
    /**
     * @param string $layout
     *
     * @return BlockBuilderInterface
     */
    public function getBlockBuilder($layout)
    {
        if (array_key_exists($layout, $this->blockBuilders)) {
            return $this->blockBuilders[$layout];
        }
        
        $newBuilder                   = new BlockBuilder();
        $this->blockBuilders[$layout] = $newBuilder;
        
        return $newBuilder;
    }
}
