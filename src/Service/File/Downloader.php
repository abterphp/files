<?php

declare(strict_types=1);

namespace AbterPhp\Files\Service\File;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Files\Authorization\FileCategoryProvider;
use AbterPhp\Files\Domain\Entities\File;
use AbterPhp\Files\Domain\Entities\FileDownload;
use AbterPhp\Files\Orm\FileDownloadRepo;
use AbterPhp\Files\Orm\FileRepo as Repo;
use AbterPhp\Framework\Authorization\Constant\Role;
use AbterPhp\Framework\Filesystem\Uploader;
use Casbin\Enforcer;
use Casbin\Exceptions\CasbinException;
use League\Flysystem\FileNotFoundException;
use Opulence\Orm\IUnitOfWork;
use Opulence\Orm\OrmException;

class Downloader
{
    const READ_LENGTH = 8192;

    /** @var Uploader */
    protected $uploader;

    /** @var Enforcer */
    protected $enforcer;

    /** @var Repo */
    protected $repo;

    /** @var FileDownloadRepo */
    protected $fileDownloadRepo;

    /** @var IUnitOfWork */
    protected $unitOfWork;

    /**
     * Download constructor.
     *
     * @param Uploader         $uploader
     * @param Enforcer         $enforcer
     * @param Repo             $repo
     * @param FileDownloadRepo $fileDownloadRepo
     * @param IUnitOfWork      $unitOfWork
     */
    public function __construct(
        Uploader $uploader,
        Enforcer $enforcer,
        Repo $repo,
        FileDownloadRepo $fileDownloadRepo,
        IUnitOfWork $unitOfWork
    ) {
        $this->uploader         = $uploader;
        $this->enforcer         = $enforcer;
        $this->repo             = $repo;
        $this->fileDownloadRepo = $fileDownloadRepo;
        $this->unitOfWork       = $unitOfWork;
    }

    /**
     * @param string    $filesystemName
     * @param User|null $user
     *
     * @return File|null
     * @throws CasbinException
     * @throws OrmException
     */
    public function getUserFile(string $filesystemName, ?User $user = null): ?File
    {
        /** @var File $entity */
        $entity = $this->repo->getByFilesystemName($filesystemName);
        if (!$entity || !$entity->getPublicName()) {
            return null;
        }

        $categoryResource = $entity->getCategory()->getIdentifier();
        if (!$this->isAllowed($categoryResource, $user)) {
            throw new CasbinException(sprintf('not allowed: %s.', $categoryResource));
        }

        return $entity;
    }

    /**
     * @param string $filesystemName
     *
     * @return File|null
     * @throws OrmException
     */
    public function getPublicFile(string $filesystemName): ?File
    {
        /** @var File $entity */
        $entity = $this->repo->getPublicByFilesystemName($filesystemName);
        if (!$entity || !$entity->getPublicName()) {
            return null;
        }

        return $entity;
    }

    /**
     * @param File $entity
     *
     * @return callable
     * @throws FileNotFoundException
     */
    public function getStream(File $entity): callable
    {
        $path   = $this->uploader->getPath(Uploader::DEFAULT_KEY, $entity->getFilesystemName());
        $stream = $this->uploader->getStream($path);
        if (!$stream) {
            throw new FileNotFoundException($path);
        }

        return $this->getReadStreamCallback($stream);
    }

    /**
     * @param string    $resourceIdentifier
     * @param User|null $user
     *
     * @return bool
     * @throws CasbinException
     */
    protected function isAllowed(string $resourceIdentifier, ?User $user = null): bool
    {
        if (!$user) {
            return false;
        }

        $username = $user->getUsername();
        $resource = sprintf('%s_%s', FileCategoryProvider::PREFIX, $resourceIdentifier);
        if (!$this->enforcer->enforce($username, $resource, Role::READ)) {
            return false;
        }

        return true;
    }

    /**
     * @param File $file
     * @param User $user
     *
     * @throws OrmException
     */
    public function logDownload(File $file, User $user)
    {
        $fileDownload = new FileDownload(0, $file, $user, new \DateTime());
        $this->fileDownloadRepo->add($fileDownload);

        $this->unitOfWork->commit();
    }

    /**
     * @param $stream
     *
     * @return callable
     */
    protected function getReadStreamCallback($stream): callable
    {
        if (!is_resource($stream)) {
            return function () {
            };
        }

        return function () use ($stream) {
            while (!feof($stream)) {
                print(@fread($stream, static::READ_LENGTH));
                ob_flush();
                flush();
            }
        };
    }
}
