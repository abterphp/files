<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Files\Domain\Entities\File;
use AbterPhp\Files\Domain\Entities\FileDownload as Entity;
use AbterPhp\Framework\Orm\IGridRepo;
use Opulence\Orm\Repositories\Repository;

class FileDownloadRepo extends Repository implements IGridRepo
{
    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $conditions
     * @param array    $params
     *
     * @return Entity[]
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $conditions, array $params): array
    {
        /** @see FileDownloadSqlDataMapper::getPage() */
        return $this->getFromDataMapper('getPage', [$limitFrom, $pageSize, $orders, $conditions, $params]);
    }

    /**
     * @param File $file
     *
     * @return Entity[]
     */
    public function getByFile(File $file): array
    {
        /** @see FileDownloadSqlDataMapper::getByFileId() */
        return $this->getFromDataMapper('getByFileId', [$file->getId()]);
    }

    /**
     * @param User $user
     *
     * @return Entity[]
     */
    public function getByUser(User $user): array
    {
        /** @see FileDownloadSqlDataMapper::getByUserId() */
        return $this->getFromDataMapper('getByUserId', [$user->getId()]);
    }
}
