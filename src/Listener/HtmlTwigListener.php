<?php

namespace Enm\Bundle\ExternalLayoutBundle\Listener;

use Enm\Bundle\ExternalLayoutBundle\Event\HtmlEvent;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class HtmlTwigListener
{
    /**
     * @param HtmlEvent $event
     */
    public function replaceTwigTags(HtmlEvent $event)
    {
        $event->getHtml()
            ->loadHTML(
                str_replace(['<twig>', '</twig>'], '', $event->getHtml()->saveHTML())
            );
    }
}
