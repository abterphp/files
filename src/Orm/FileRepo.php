<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Orm\DataMappers\FileSqlDataMapper;
use AbterPhp\Framework\Orm\IGridRepo;
use Opulence\Orm\Repositories\Repository;

class FileRepo extends Repository implements IGridRepo
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
        /** @see FileSqlDataMapper::getPage() */
        return $this->getFromDataMapper('getPage', [$limitFrom, $pageSize, $orders, $conditions, $params]);
    }

    /**
     * @param User $user
     *
     * @return Entity[]
     * @throws \Opulence\Orm\OrmException
     */
    public function getByUser(User $user): array
    {
        /** @see FileSqlDataMapper::getByUserId() */
        return $this->getFromDataMapper('getByUserId', [$user->getId()]);
    }

    /**
     * @param string $filesystemName
     *
     * @return Entity|null
     * @throws \Opulence\Orm\OrmException
     */
    public function getByFilesystemName(string $filesystemName): ?Entity
    {
        /** @see FileSqlDataMapper::getByFilesystemName() */
        return $this->getFromDataMapper('getByFilesystemName', [$filesystemName]);
    }

    /**
     * @param string $filesystemName
     *
     * @return Entity|null
     * @throws \Opulence\Orm\OrmException
     */
    public function getPublicByFilesystemName(string $filesystemName): ?Entity
    {
        /** @see FileSqlDataMapper::getPublicByFilesystemName() */
        return $this->getFromDataMapper('getPublicByFilesystemName', [$filesystemName]);
    }

    /**
     * @param string[] $identifiers
     *
     * @return Entity[]
     * @throws \Opulence\Orm\OrmException
     */
    public function getPublicByCategoryIdentifiers(array $identifiers): array
    {
        /** @see FileSqlDataMapper::getPublicByCategoryIdentifiers() */
        return $this->getFromDataMapper('getPublicByCategoryIdentifiers', [$identifiers]);
    }
}
