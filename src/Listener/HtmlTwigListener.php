<?php

namespace Enm\Bundle\ExternalLayoutBundle\Listener;

use Enm\Bundle\ExternalLayoutBundle\Event\HtmlStringEvent;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class HtmlTwigListener
{
    /**
     * @param HtmlStringEvent $event
     */
    public function replaceTwigTags(HtmlStringEvent $event)
    {
        $event->setHtml(str_replace(['<twig>', '</twig>'], '', $event->getHtml()));
    }
}
