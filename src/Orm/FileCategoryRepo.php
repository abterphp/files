<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Files\Orm\DataMappers\FileCategorySqlDataMapper; // @phan-suppress-current-line PhanUnreferencedUseNormal
use AbterPhp\Framework\Orm\IGridRepo;
use Opulence\Orm\Repositories\Repository;

class FileCategoryRepo extends Repository implements IGridRepo
{
    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $conditions
     * @param array    $params
     *
     * @return Entity[]
     * @throws \Opulence\Orm\OrmException
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $conditions, array $params): array
    {
        /** @see FileCategorySqlDataMapper::getPage() */
        return $this->getFromDataMapper('getPage', [$limitFrom, $pageSize, $orders, $conditions, $params]);
    }

    /**
     * @param UserGroup $userGroup
     *
     * @return Entity[]
     * @throws \Opulence\Orm\OrmException
     */
    public function getByUserGroup(UserGroup $userGroup): array
    {
        /** @see FileCategorySqlDataMapper::getByUserGroupId() */
        return $this->getFromDataMapper('getByUserGroupId', [$userGroup->getId()]);
    }
}
