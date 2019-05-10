<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory;

use AbterPhp\Files\Domain\Entities\FileDownload as Entity;
use AbterPhp\Files\Grid\Factory\Table\FileDownload as Table;
use AbterPhp\Files\Grid\Filters\FileDownload as Filters;
use AbterPhp\Framework\Grid\Factory\BaseFactory;
use AbterPhp\Framework\Grid\Factory\GridFactory;
use AbterPhp\Framework\Grid\Factory\PaginationFactory as PaginationFactory;
use AbterPhp\Framework\Helper\DateHelper;
use Opulence\Routing\Urls\UrlGenerator;

class FileDownload extends BaseFactory
{
    const GROUP_FILE          = 'fileDownload-file';
    const GROUP_USER          = 'fileDownload-user';
    const GROUP_DOWNLOADED_AT = 'fileDownload-downloaded-at';

    const GETTER_FILE          = 'getFile';
    const GETTER_USER          = 'getUser';
    const GETTER_DOWNLOADED_AT = 'getDownloadedAt';

    /**
     * FileDownload constructor.
     *
     * @param UrlGenerator      $urlGenerator
     * @param PaginationFactory $paginationFactory
     * @param Table             $tableFactory
     * @param GridFactory       $gridFactory
     * @param Filters           $filters
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        PaginationFactory $paginationFactory,
        Table $tableFactory,
        GridFactory $gridFactory,
        Filters $filters
    ) {
        parent::__construct($urlGenerator, $paginationFactory, $tableFactory, $gridFactory, $filters);
    }

    /**
     * @return array
     */
    public function getGetters(): array
    {
        return [
            static::GROUP_FILE          => static::GETTER_FILE,
            static::GROUP_USER          => static::GETTER_USER,
            /** @see FileDownload::getDownloadedAt() */
            static::GROUP_DOWNLOADED_AT => [$this, 'getDownloadedAt'],
        ];
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public function getDownloadedAt(Entity $entity): string
    {
        return DateHelper::formatDateTime($entity->getDownloadedAt());
    }
}
