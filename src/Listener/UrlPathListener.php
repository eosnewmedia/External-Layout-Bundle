<?php

namespace Enm\Bundle\ExternalLayoutBundle\Listener;

use Enm\Bundle\ExternalLayoutBundle\Event\HtmlEvent;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class UrlPathListener
{
    /**
     * @var array
     */
    private $layouts;

    /**
     * @param array $layouts
     */
    public function __construct(array $layouts)
    {
        $this->layouts = $layouts;
    }

    /**
     * @param HtmlEvent $event
     *
     * @return void
     */
    public function onHtmlLoaded(HtmlEvent $event)
    {
        $dom = $event->getHtml();

        $scheme = $this->layouts[$event->getLayout()]['source']['scheme'];
        $host = $scheme . '://' . $this->layouts[$event->getLayout()]['source']['host'];

        /** @var \DOMElement $anchor */
        foreach ($dom->getElementsByTagName('a') as $anchor) {
            $this->replaceHref($anchor, 'href', $host);
        }

        /** @var \DOMElement $anchor */
        foreach ($dom->getElementsByTagName('link') as $anchor) {
            $this->replaceHref($anchor, 'href', $host);
        }


        /** @var \DOMElement $anchor */
        foreach ($dom->getElementsByTagName('img') as $anchor) {
            $this->replaceHref($anchor, 'src', $host);
        }
    }

    /**
     * @param \DOMElement $element
     * @param $attribute
     * @param $host
     *
     * @return void
     */
    protected function replaceHref(\DOMElement $element, $attribute, $host)
    {
        $link = $element->getAttribute($attribute);

        $components = parse_url($link);
        $noScheme = !array_key_exists('scheme', $components);
        $noHost = !array_key_exists('host', $components);

        if ($noScheme && $noHost) {
            $link = $host . (strpos($link, '/') !== 0 ? '/' : '') . $link;
        }

        $element->setAttribute($attribute, $link);
    }
}
