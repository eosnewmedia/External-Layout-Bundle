<?php

namespace Enm\Bundle\ExternalLayoutBundle\SourceLoader;

use GuzzleHttp\Client;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class SourceLoader implements SourceLoaderInterface
{
    /**
     * @param string $url
     *
     * @return string
     * @throws \LogicException
     */
    public function loadHtml($url)
    {
        $client = new Client();

        $response = $client->get($url);

        $contentType = strtolower($response->getHeader('Content-Type')[0]);
        if (strpos($contentType, 'text/html') !== 0) {
            throw new \LogicException('HTML can\'t be loaded from remote server!');
        }

        $html = (string)$response->getBody();

        if (strpos($contentType, 'charset=') !== false && strpos($contentType, 'utf-8') === false) {
            $html = (string)preg_replace('/charset=\"([-a-zA-Z0-9_]+)\"/', 'charset="utf-8"', $html);
            $html = (string)preg_replace('/charset=([-a-zA-Z0-9_]+)/', 'charset=utf-8', $html);
            $html = (string)preg_replace('/encoding=\"([-a-zA-Z0-9_]+)\"/', 'encoding="utf-8"', $html);
            $html = utf8_encode($html);
        }

        return $html;
    }
}
