<?php

declare(strict_types=1);

namespace AbterPhp\Files\Events\Listeners;

use AbterPhp\Files\Constant\Routes;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\ButtonFactory;
use AbterPhp\Framework\Html\ITag;
use AbterPhp\Framework\Navigation\Dropdown;
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
    /**
     * @param NavigationReady $event
     *
     * @throws \Opulence\Routing\Urls\UrlException
     */
    public function handle(NavigationReady $event)
    {
        $navigation = $event->getNavigation();

        if (!$navigation->hasIntent(Navigation::INTENT_PRIMARY)) {
            return;
        }

        $item   = $this->createFilesItem();

        $navigation->addItem($item, static::BASE_WEIGHT);
    }

    /**
     * @return Item
     * @throws \Opulence\Routing\Urls\UrlException
     */
    protected function createFileCategoriesItem(): Item
    {
        $text = 'files:fileCategories';
        $icon = 'folder';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_FILE_CATEGORIES, [], $icon);
        $resource = $this->getAdminResource(Routes::ROUTE_FILE_CATEGORIES);

        $item = new Item($button);
        $item->setResource($resource);

        return $item;
    }

    /**
     * @return Item
     * @throws \Opulence\Routing\Urls\UrlException
     */
    protected function createFilesItem(): Item
    {
        $text = 'files:files';
        $icon = 'attachment';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_FILES, [], $icon);
        $resource = $this->getAdminResource(Routes::ROUTE_FILES);

        $item = new Item($button);
        $item->setResource($resource);

        $item->setIntent(Item::INTENT_DROPDOWN);
        $item->setAttribute(Html5::ATTR_ID, 'nav-files');

        if (count($item) > 0 && $item instanceof ITag) {
            $item[0]->setAttribute(Html5::ATTR_HREF, 'javascript:void(0);');
        }

        $item[] = $this->createDropdown();

        return $item;
    }

    /**
     * @return Dropdown
     * @throws \Opulence\Routing\Urls\UrlException
     */
    protected function createDropdown(): Dropdown
    {
        $dropdown = new Dropdown();
        $dropdown[] = $this->createFileCategoriesItem();
        $dropdown[] = $this->createFilesItem();
        $dropdown[] = $this->createFileDownloadsItem();

        return $dropdown;
    }

    /**
     * @return Item
     * @throws \Opulence\Routing\Urls\UrlException
     */
    protected function createFileDownloadsItem(): Item
    {
        $text = 'files:fileDownloads';
        $icon = 'file_download';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_FILE_DOWNLOADS, [], $icon);
        $resource = $this->getAdminResource(Routes::ROUTE_FILE_DOWNLOADS);

        $item = new Item($button);
        $item->setResource($resource);

        return $item;
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
