<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Api;

use AbterPhp\Files\Service\Execute\FileCategory as RepoService;
use AbterPhp\Framework\Config\Provider as ConfigProvider;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Framework\Http\Controllers\ApiAbstract;
use Psr\Log\LoggerInterface;

class FileCategory extends ApiAbstract
{
    const ENTITY_SINGULAR = 'fileCategory';
    const ENTITY_PLURAL   = 'fileCategories';

    /**
     * FileCategory constructor.
     *
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     * @param FoundRows       $foundRows
     * @param ConfigProvider  $configProvider
     */
    public function __construct(
        LoggerInterface $logger,
        RepoService $repoService,
        FoundRows $foundRows,
        ConfigProvider $configProvider
    ) {
        parent::__construct($logger, $repoService, $foundRows, $configProvider);
    }

    /**
     * @return array
     */
    public function getSharedData(): array
    {
        $data = $this->request->getJsonBody();

        if (array_key_exists('password', $data)) {
            $data['password_repeated'] = $data['password'];
        }

        return $data;
    }
}
