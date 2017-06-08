<?php

namespace Enm\Bundle\ExternalLayoutBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class HtmlEvent extends Event
{
    /**
     * @var string
     */
    private $layout;
    /**
     * @var \DOMDocument
     */
    private $html;

    /**
     * HtmlEvent constructor.
     *
     * @param string $layout
     * @param string $html
     */
    public function __construct($layout, $html)
    {
        libxml_use_internal_errors(true);
        libxml_disable_entity_loader(true);

        $this->layout = $layout;
        $this->setHtmlFromString($html);
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @return \DOMDocument
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param \DOMDocument $html
     */
    public function setHtml(\DOMDocument $html)
    {
        $this->html = $html;
    }

    /**
     * @param string $html
     */
    public function setHtmlFromString($html)
    {
        $this->html = new \DOMDocument();
        $this->html->loadHTML($html);
    }
}
