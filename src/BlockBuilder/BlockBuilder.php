<?php
namespace Enm\Bundle\ExternalLayoutBundle\BlockBuilder;

use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class BlockBuilder implements BlockBuilderInterface
{
    /**
     * BlockBuilder constructor.
     */
    public function __construct()
    {
        libxml_use_internal_errors(true);
        libxml_disable_entity_loader(true);
    }
    
    /**
     * Add a Twig Block as first child of the given selector
     *
     * @param string $html The current html string
     * @param string $selector The css selector of the dom node under which the new block should be added
     * @param string $block The block name
     *
     * @return string The new html string
     */
    public function prependBlock($html, $selector, $block)
    {
        $dom  = $this->loadHtmlToDom($html);
        $node = $this->getSelectedNode($dom, $selector);
        
        $node->insertBefore(
          $dom->createElement('twig', $this->buildTwigBlock($block)),
          $node->firstChild
        );
        
        return $dom->saveHTML();
    }
    
    /**
     * Add a Twig Block as last child of the given selector
     *
     * @param string $html The current html string
     * @param string $selector The css selector of the dom node under which the new block should be added
     * @param string $block The block name
     *
     * @return string The new html string
     */
    public function appendBlock($html, $selector, $block)
    {
        $dom  = $this->loadHtmlToDom($html);
        $node = $this->getSelectedNode($dom, $selector);
        
        $node->appendChild(
          $dom->createElement('twig', $this->buildTwigBlock($block))
        );
        
        return $dom->saveHTML();
    }
    
    /**
     * Replace a placeholder in the html with a twig block
     *
     * @param string $html The current html string
     * @param string $placeholder The string to be replaced with the block
     * @param string $block The block name
     *
     * @return string The new html string
     */
    public function replaceWithBlock($html, $placeholder, $block)
    {
        return str_replace(
          $placeholder,
          '<twig>'.$this->buildTwigBlock($block).'</twig>',
          $html
        );
    }
    
    /**
     * @param string $block
     *
     * @return string
     */
    private function buildTwigBlock($block)
    {
        return '{% block '.$block.' %}{% endblock %}';
    }
    
    /**
     * @param string $html
     *
     * @return \DOMDocument
     */
    private function loadHtmlToDom($html)
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        
        return $dom;
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
