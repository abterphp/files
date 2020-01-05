<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory;

use AbterPhp\Admin\Grid\Factory\BaseFactory;
use AbterPhp\Admin\Grid\Factory\GridFactory;
use AbterPhp\Admin\Grid\Factory\PaginationFactory;
use AbterPhp\Files\Domain\Entities\FileDownload as Entity;
use AbterPhp\Files\Grid\Factory\Table\FileDownload as TableFactory;
use AbterPhp\Files\Grid\Factory\Table\Header\FileDownload as HeaderFactory;
use AbterPhp\Files\Grid\Filters\FileDownload as Filters;
use AbterPhp\Framework\Helper\DateHelper;
use Opulence\Routing\Urls\UrlGenerator;

class FileDownload extends BaseFactory
{
    private const GETTER_FILE = 'getFile';
    private const GETTER_USER = 'getUser';

    /**
     * FileDownload constructor.
     *
     * @param UrlGenerator      $urlGenerator
     * @param PaginationFactory $paginationFactory
     * @param TableFactory      $tableFactory
     * @param GridFactory       $gridFactory
     * @param Filters           $filters
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        PaginationFactory $paginationFactory,
        TableFactory $tableFactory,
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
            HeaderFactory::GROUP_FILE          => static::GETTER_FILE,
            HeaderFactory::GROUP_USER          => static::GETTER_USER,
            /** @see FileDownload::getDownloadedAt() */
            HeaderFactory::GROUP_DOWNLOADED_AT => [$this, 'getDownloadedAt'],
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
