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
            $this->replaceElementAttributeWithAbsoluteUri($anchor, 'href', $host);
        }

        /** @var \DOMElement $anchor */
        foreach ($dom->getElementsByTagName('link') as $anchor) {
            $this->replaceElementAttributeWithAbsoluteUri($anchor, 'href', $host);
        }

        /** @var \DOMElement $anchor */
        foreach ($dom->getElementsByTagName('img') as $anchor) {
            $this->replaceElementAttributeWithAbsoluteUri($anchor, 'src', $host);
        }

        /** @var \DOMElement $anchor */
        foreach ($dom->getElementsByTagName('script') as $anchor) {
            $this->replaceElementAttributeWithAbsoluteUri($anchor, 'src', $host);
        }

        $xPath = new \DOMXPath($dom);
        /** @var \DOMComment[] $comments */
        $comments = $xPath->query('//comment()');
        foreach ($comments as $comment) {
            $this->replaceCommentWithAbsoluteUri($comment, $host);
        }
    }

    /**
     * @param \DOMElement $element
     * @param $attribute
     * @param $host
     *
     * @return void
     */
    protected function replaceElementAttributeWithAbsoluteUri(\DOMElement $element, $attribute, $host)
    {
        $uri = $element->getAttribute($attribute);

        $uri = $this->convertToAbsoluteUri($host, $uri);

        $element->setAttribute($attribute, $uri);
    }

    /**
     * @param \DOMComment $comment
     * @param string $host
     * @return void
     */
    protected function replaceCommentWithAbsoluteUri(\DOMComment $comment, $host)
    {
        if (preg_match_all('/(href|src)=\"([a-zA-Z0-9\-\/\.\?\#]+)\"/', $comment->textContent, $matches)) {
            /** @var array $uris */
            $uris = array_key_exists(2, $matches) && is_array($matches[2]) ? $matches[2] : [];
            foreach ($uris as $uri) {
                $comment->textContent = str_replace(
                    $uri,
                    $this->convertToAbsoluteUri($host, $uri),
                    $comment->textContent
                );
            }
        }
    }

    /**
     * @param $host
     * @param $uri
     * @return string
     */
    protected function convertToAbsoluteUri($host, $uri)
    {
        $components = parse_url($uri);
        $noScheme = !array_key_exists('scheme', $components);
        $noHost = !array_key_exists('host', $components);

        if ($noScheme && $noHost) {
            $uri = $host . (strpos($uri, '/') !== 0 ? '/' : '') . $uri;
        }
        return $uri;
    }
}
