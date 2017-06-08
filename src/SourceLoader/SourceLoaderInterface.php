<?php
namespace Enm\Bundle\ExternalLayoutBundle\SourceLoader;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface SourceLoaderInterface
{
    /**
     * @param string $url
     *
     * @return string
     */
    public function loadHtml($url);
}
