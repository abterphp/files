<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm\DataMappers;

use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use Opulence\Orm\DataMappers\IDataMapper;

interface IFileCategoryDataMapper extends IDataMapper
{
    /**
     * @param string $userGroupId
     *
     * @return Entity[]
     */
    public function getByUserGroupId(string $userGroupId): array;

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
