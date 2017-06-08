<?php
namespace Enm\Bundle\ExternalLayoutBundle\BlockBuilder;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface BlockBuilderInterface
{
    /**
     * Add a Twig Block as first child of the given selector
     *
     * @param string $html The current html string
     * @param string $selector The css selector of the dom node under which the new block should be added
     * @param string $block The block name
     *
     * @return string The new html string
     */
    public function prependBlock($html, $selector, $block);
    
    /**
     * Add a Twig Block as last child of the given selector
     *
     * @param string $html The current html string
     * @param string $selector The css selector of the dom node under which the new block should be added
     * @param string $block The block name
     *
     * @return string The new html string
     */
    public function appendBlock($html, $selector, $block);
    
    /**
     * Replace a placeholder in the html with a twig block
     *
     * @param string $html The current html string
     * @param string $placeholder The string to be replaced with the block
     * @param string $block The block name
     *
     * @return string The new html string
     */
    public function replaceWithBlock($html, $placeholder, $block);
}
