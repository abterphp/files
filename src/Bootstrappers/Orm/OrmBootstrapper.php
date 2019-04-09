<?php

declare(strict_types=1);

namespace AbterPhp\Files\Bootstrappers\Orm;

use AbterPhp\Admin\Bootstrappers\Orm\OrmBootstrapper as AbterAdminOrmBootstrapper;
use AbterPhp\Files\Domain\Entities\File;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Files\Domain\Entities\FileDownload;
use AbterPhp\Files\Orm\DataMappers\FileCategorySqlDataMapper;
use AbterPhp\Files\Orm\DataMappers\FileDownloadSqlDataMapper;
use AbterPhp\Files\Orm\DataMappers\FileSqlDataMapper;
use AbterPhp\Files\Orm\FileCategoryRepo;
use AbterPhp\Files\Orm\FileDownloadRepo;
use AbterPhp\Files\Orm\FileRepo;
use Opulence\Ioc\IContainer;
use Opulence\Ioc\IocException;
use Opulence\Orm\IUnitOfWork;
use RuntimeException;

class OrmBootstrapper extends AbterAdminOrmBootstrapper
{
    /** @var array */
    protected $repoMappers = [
        FileDownloadRepo::class => [FileDownloadSqlDataMapper::class, FileDownload::class],
        FileCategoryRepo::class => [FileCategorySqlDataMapper::class, FileCategory::class],
        FileRepo::class         => [FileSqlDataMapper::class, File::class],
    ];

    /**
     * @inheritdoc
     */
    public function getBindings(): array
    {
        return array_keys($this->repoMappers);
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        try {
            $unitOfWork = $container->resolve(IUnitOfWork::class);
            $this->bindRepositories($container, $unitOfWork);
        } catch (IocException $ex) {
            $namespace = explode('\\', __NAMESPACE__)[0];
            throw new RuntimeException("Failed to register $namespace bindings", 0, $ex);
        }
    }
}
