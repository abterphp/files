<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Api;

use AbterPhp\Files\Service\Execute\Api\File as RepoService;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Framework\Http\Controllers\Admin\ApiAbstract;
use League\Flysystem\Filesystem;
use Psr\Log\LoggerInterface;

class File extends ApiAbstract
{
    const FILENAMESYSTEM_NAME_LENGTH = 6;

    const ENTITY_SINGULAR = 'file';
    const ENTITY_PLURAL   = 'files';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * File constructor.
     *
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     * @param FoundRows       $foundRows
     * @param Filesystem      $filesystem
     */
    public function __construct(
        LoggerInterface $logger,
        RepoService $repoService,
        FoundRows $foundRows,
        Filesystem $filesystem
    ) {
        parent::__construct($logger, $repoService, $foundRows);

        $this->filesystem = $filesystem;
    }

    /**
     * @return array
     */
    public function getSharedData(): array
    {
        $data = $this->request->getJsonBody();

        $path    = \bin2hex(\random_bytes(static::FILENAMESYSTEM_NAME_LENGTH));
        $content = base64_decode($data['data'], true);

        $this->filesystem->write($path, $content);

        $data['filesystem_name'] = $path;
        $data['public_name']     = $data['name'];

        return $data;
    }
}
