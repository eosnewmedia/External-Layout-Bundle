<?php

namespace Enm\Bundle\ExternalLayoutBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class HtmlStringEvent extends Event
{
    /**
     * @var string
     */
    private $layout;

    /**
     * @var string
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
        $this->setHtml($html);
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param string $html
     *
     * @return $this
     */
    public function setHtml($html)
    {
        $this->html = (string)$html;

        return $this;
    }
}
