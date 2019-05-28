<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Api;

use AbterPhp\Files\Service\Execute\File as RepoService;
use AbterPhp\Framework\Http\Controllers\Admin\ApiAbstract;
use Opulence\Http\Responses\Response;
use Opulence\Routing\Controller;
use Psr\Log\LoggerInterface;

class File extends ApiAbstract
{
    const ENTITY_SINGULAR = 'file';
    const ENTITY_PLURAL   = 'files';

    /**
     * File constructor.
     *
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     */
    public function __construct(LoggerInterface $logger, RepoService $repoService)
    {
        parent::__construct($logger, $repoService);
    }
}
