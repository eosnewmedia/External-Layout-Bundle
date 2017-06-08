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
     * @param \DOMDocument|string $html The current html string
     * @param string $selector The css selector of the dom node under which the new block should be added
     * @param string $block The block name
     *
     * @return void
     */
    public function prependBlock(\DOMDocument $html, $selector, $block);

    /**
     * Add a Twig Block as last child of the given selector
     *
     * @param \DOMDocument|string $html The current html string
     * @param string $selector The css selector of the dom node under which the new block should be added
     * @param string $block The block name
     *
     * @return void
     */
    public function appendBlock(\DOMDocument $html, $selector, $block);

    /**
     * Replace a placeholder in the html with a twig block
     *
     * @param \DOMDocument|string $html The current html string
     * @param string $placeholder The string to be replaced with the block
     * @param string $block The block name
     *
     * @return void
     */
    public function replaceWithBlock(\DOMDocument $html, $placeholder, $block);
}
