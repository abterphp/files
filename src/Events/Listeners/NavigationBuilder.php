<?php

declare(strict_types=1);

namespace AbterPhp\Files\Events\Listeners;

use AbterPhp\Files\Constant\Routes;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\ButtonFactory;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Navigation\Item;
use AbterPhp\Framework\Navigation\Navigation;

class NavigationBuilder
{
    const BASE_WEIGHT = 600;

    /** @var ButtonFactory */
    protected $buttonFactory;

    /**
     * NavigationRegistrar constructor.
     *
     * @param ButtonFactory $buttonFactory
     */
    public function __construct(ButtonFactory $buttonFactory)
    {
        $this->buttonFactory = $buttonFactory;
    }

    /**
     * @param NavigationReady $event
     */
    public function handle(NavigationReady $event)
    {
        $navigation = $event->getNavigation();

        if (!$navigation->hasIntent(Navigation::INTENT_PRIMARY)) {
            return;
        }

        $this->addFileCategories($navigation);
        $this->addFiles($navigation);
        $this->addFileDownloads($navigation);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addFileCategories(Navigation $navigation)
    {
        $text = 'files:fileCategories';
        $icon = 'folder';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_FILE_CATEGORIES, [], $icon);
        $resource = $this->getAdminResource(Routes::ROUTE_FILE_CATEGORIES);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addFiles(Navigation $navigation)
    {
        $text = 'files:files';
        $icon = 'attachment';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_FILES, [], $icon);
        $resource = $this->getAdminResource(Routes::ROUTE_FILES);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addFileDownloads(Navigation $navigation)
    {
        $text = 'files:fileDownloads';
        $icon = 'file_download';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_FILE_DOWNLOADS, [], $icon);
        $resource = $this->getAdminResource(Routes::ROUTE_FILE_DOWNLOADS);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    protected function getAdminResource(string $resource): string
    {
        return sprintf('admin_resource_%s', $resource);
    }
}
