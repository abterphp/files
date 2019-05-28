<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Api;

use AbterPhp\Files\Service\Execute\FileDownload as RepoService;
use AbterPhp\Framework\Http\Controllers\Admin\ApiAbstract;
use Opulence\Http\Responses\Response;
use Opulence\Routing\Controller;
use Psr\Log\LoggerInterface;

class FileDownload extends ApiAbstract
{
    const ENTITY_SINGULAR = 'fileDownload';
    const ENTITY_PLURAL   = 'fileDownloads';

    /**
     * FileDownload constructor.
     *
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     */
    public function __construct(LoggerInterface $logger, RepoService $repoService)
    {
        parent::__construct($logger, $repoService);
    }
}
