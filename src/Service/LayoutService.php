<?php
namespace Enm\Bundle\ExternalLayoutBundle\Service;

use Enm\Bundle\ExternalLayoutBundle\BlockBuilder\BlockBuilderRegistry;
use Enm\Bundle\ExternalLayoutBundle\Event\HtmlEvent;
use Enm\Bundle\ExternalLayoutBundle\Events;
use Enm\Bundle\ExternalLayoutBundle\SourceLoader\SourceLoaderRegistry;
use GuzzleHttp\Psr7\Uri;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class LayoutService
{
    /**
     * @var SourceLoaderRegistry
     */
    private $sourceLoaderRegistry;

    /**
     * @var BlockBuilderRegistry
     */
    private $blockBuilderRegistry;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var string
     */
    private $layout;

    /**
     * @var HtmlEvent
     */
    private $html;

    /**
     * LayoutService constructor.
     *
     * @param SourceLoaderRegistry $sourceLoaderRegistry
     * @param BlockBuilderRegistry $blockBuilderRegistry
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(SourceLoaderRegistry $sourceLoaderRegistry, BlockBuilderRegistry $blockBuilderRegistry, EventDispatcherInterface $dispatcher)
    {
        $this->sourceLoaderRegistry = $sourceLoaderRegistry;
        $this->blockBuilderRegistry = $blockBuilderRegistry;
        $this->dispatcher           = $dispatcher;
    }

    /**
     * @return SourceLoaderRegistry
     */
    public function getSourceLoaderRegistry()
    {
        return $this->sourceLoaderRegistry;
    }

    /**
     * @return BlockBuilderRegistry
     */
    public function getBlockBuilderRegistry()
    {
        return $this->blockBuilderRegistry;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param string $layout
     * @param array $config
     *
     * @return $this
     * @throws \Exception
     */
    public function createLayout($layout, array $config)
    {
        $this->layout = $layout;

        $this->loadHtml($config['source']);

        $this->getDispatcher()
            ->dispatch(Events::HTML_LOADED, $this->html);

        $this->addBlocks(
            $config['blocks']['prepend'],
            $config['blocks']['append']
        );

        $this->replacePlaceholdersWithBlocks($config['blocks']['replace']);

        $this->getDispatcher()
            ->dispatch(Events::HTML_MANIPULATED, $this->html);

        $this->dumpHtmlToFile();

        return $this;
    }

    /**
     * @param array $source
     *
     * @return $this
     */
    private function loadHtml(array $source)
    {
        $url = (new Uri())
            ->withScheme($source['scheme'])
            ->withHost($source['host'])
            ->withPath($source['path'])
            ->withUserInfo($source['user'], $source['password']);

        $loader = $this->getSourceLoaderRegistry()
            ->getSourceLoader($this->layout);

        $this->html = new HtmlEvent($this->layout, $loader->loadHtml($url));

        return $this;
    }

    /**
     * @param array $prepend
     * @param array $append
     *
     * @return $this
     */
    private function addBlocks(array $prepend, array $append)
    {
        $builder = $this->getBlockBuilderRegistry()
            ->getBlockBuilder($this->layout);

        foreach ($prepend as $block => $selector) {
            $this->html->setHtml(
                $builder->prependBlock(
                    $this->html->getHtml(),
                    $selector,
                    $block
                )
            );
        }

        foreach ($append as $block => $selector) {
            $this->html->setHtml(
                $builder->appendBlock(
                    $this->html->getHtml(),
                    $selector,
                    $block
                )
            );
        }

        return $this;
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    private function replacePlaceholdersWithBlocks(array $config)
    {
        $builder = $this->getBlockBuilderRegistry()
            ->getBlockBuilder($this->layout);
        foreach ($config as $block => $placeholder) {
            $this->html->setHtml(
                $builder->replaceWithBlock(
                    $this->html->getHtml(),
                    $placeholder,
                    $block
                )
            );
        }

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function dumpHtmlToFile()
    {
        $file = __DIR__.'/../Resources/views/'.$this->layout.'.html.twig';

        $fs = new Filesystem();
        $fs->dumpFile($file, $this->html->getHtml());

        return $this;
    }
}
