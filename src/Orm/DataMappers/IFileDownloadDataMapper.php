<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm\DataMappers;

use AbterPhp\Files\Domain\Entities\FileDownload as Entity;
use Opulence\Orm\DataMappers\IDataMapper;

interface IFileDownloadDataMapper extends IDataMapper
{
    /**
     * @param string $fileId
     *
     * @return Entity[]
     */
    public function getByFileId(string $fileId): array;

    /**
     * @param string $userId
     *
     * @return Entity[]
     */
    public function getByUserId(string $userId): array;

    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $filters
     * @param array    $params
     *
     * @return Entity[]
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $filters, array $params): array;
}
