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
        $this->layout = $layout;
        $this->html   = $html;
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
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }
}
