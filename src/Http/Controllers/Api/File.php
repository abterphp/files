<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Api;

use AbterPhp\Admin\Http\Controllers\ApiAbstract;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Service\Execute\Api\File as RepoService;
use AbterPhp\Framework\Config\EnvReader;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use League\Flysystem\Filesystem;
use Opulence\Http\Responses\Response;
use Psr\Log\LoggerInterface;

class File extends ApiAbstract
{
    const FILENAMESYSTEM_NAME_LENGTH = 6;

    const ENTITY_SINGULAR = 'file';
    const ENTITY_PLURAL   = 'files';

    /** @var Filesystem */
    protected $filesystem;

    /** @var RepoService */
    protected $repoService;

    /**
     * File constructor.
     *
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     * @param FoundRows       $foundRows
     * @param EnvReader       $envReader
     * @param Filesystem      $filesystem
     */
    public function __construct(
        LoggerInterface $logger,
        RepoService $repoService,
        FoundRows $foundRows,
        EnvReader $envReader,
        Filesystem $filesystem
    ) {
        parent::__construct($logger, $repoService, $foundRows, $envReader);

        $this->filesystem = $filesystem;
    }

    /**
     * @param string $entityId
     *
     * @return Response
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function get(string $entityId): Response
    {
        try {
            $entity = $this->repoService->retrieveEntity($entityId);
        } catch (\Exception $e) {
            $msg = sprintf(static::LOG_MSG_GET_FAILURE, static::ENTITY_SINGULAR);

            return $this->handleException($msg, $e);
        }

        if (!($entity instanceof Entity)) {
            throw new \RuntimeException('Invalid entity');
        }

        if ($this->request->getQuery()->get('embed') === 'data') {
            $content = $this->filesystem->read($entity->getFilesystemName());

            $entity->setContent(base64_encode($content));
        }

        return $this->handleGetSuccess($entity);
    }

    /**
     * @return array
     * @throws \League\Flysystem\FileExistsException
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
