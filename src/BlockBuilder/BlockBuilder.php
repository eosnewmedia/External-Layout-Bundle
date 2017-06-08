<?php

namespace Enm\Bundle\ExternalLayoutBundle\BlockBuilder;

use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class BlockBuilder implements BlockBuilderInterface
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
    public function prependBlock(\DOMDocument $html, $selector, $block)
    {
        $node = $this->getSelectedNode($html, $selector);

        $node->insertBefore(
            $html->createElement('twig', $this->buildTwigBlock($block)),
            $node->firstChild
        );
    }

    /**
     * Add a Twig Block as last child of the given selector
     *
     * @param \DOMDocument|string $html The current html string
     * @param string $selector The css selector of the dom node under which the new block should be added
     * @param string $block The block name
     *
     * @return void
     */
    public function appendBlock(\DOMDocument $html, $selector, $block)
    {
        $node = $this->getSelectedNode($html, $selector);

        $node->appendChild(
            $html->createElement('twig', $this->buildTwigBlock($block))
        );
    }

    /**
     * Replace a placeholder in the html with a twig block
     *
     * @param \DOMDocument|string $html The current html string
     * @param string $placeholder The string to be replaced with the block
     * @param string $block The block name
     *
     * @return void
     */
    public function replaceWithBlock(\DOMDocument $html, $placeholder, $block)
    {
        $html->loadHTML(
            str_replace(
                $placeholder,
                '<twig>' . $this->buildTwigBlock($block) . '</twig>',
                $html->saveHTML()
            )
        );
    }

    /**
     * @param string $block
     *
     * @return string
     */
    private function buildTwigBlock($block)
    {
        return '{% block ' . $block . ' %}{% endblock %}';
    }

    /**
     * @param \DOMDocument $dom
     * @param string $selector
     *
     * @return \DOMNode
     */
    private function getSelectedNode(\DOMDocument $dom, $selector)
    {
        $xPath = new \DOMXPath($dom);

        return $xPath->query((new CssSelectorConverter())->toXPath($selector))
            ->item(0);
    }
}
