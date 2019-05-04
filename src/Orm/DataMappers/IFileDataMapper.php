<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm\DataMappers;

use AbterPhp\Files\Domain\Entities\File as Entity;
use Opulence\Orm\DataMappers\IDataMapper;

interface IFileDataMapper extends IDataMapper
{
    /**
     * @param string $userId
     *
     * @return Entity[]
     */
    public function getByUserId(string $userId): array;

    /**
     * @param string $filesystemName
     *
     * @return Entity
     */
    public function getByFilesystemName(string $filesystemName): Entity;

    /**
     * @param string $filesystemName
     *
     * @return Entity
     */
    public function getPublicByFilesystemName(string $filesystemName): Entity;

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

    /**
     * @param string[] $identifiers
     *
     * @return Entity[]
     * @throws \Opulence\Orm\OrmException
     */
    public function getPublicByCategoryIdentifiers(array $identifiers): array;
}
