<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Admin\Grid;

use AbterPhp\Admin\Http\Controllers\Admin\GridAbstract;
use AbterPhp\Files\Service\RepoGrid\FileDownload as RepoGrid;
use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;
use Psr\Log\LoggerInterface;

class FileDownload extends GridAbstract
{
    const ENTITY_PLURAL   = 'fileDownloads';
    const ENTITY_SINGULAR = 'fileDownload';

    const ENTITY_TITLE_PLURAL = 'files:fileDownloads';

    /** @var string */
    protected $resource = 'file_downloads';

    /**
     * FileDownload constructor.
     *
     * @param FlashService     $flashService
     * @param ITranslator      $translator
     * @param UrlGenerator     $urlGenerator
     * @param LoggerInterface  $logger
     * @param AssetManager     $assets
     * @param RepoGrid         $repoGrid
     * @param IEventDispatcher $eventDispatcher
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        LoggerInterface $logger,
        AssetManager $assets,
        RepoGrid $repoGrid,
        IEventDispatcher $eventDispatcher
    ) {
        parent::__construct(
            $flashService,
            $translator,
            $urlGenerator,
            $logger,
            $assets,
            $repoGrid,
            $eventDispatcher
        );
    }

    /**
     * @return string
     */
    protected function getCreateUrl(): string
    {
        return '';
    }
}
