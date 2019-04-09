<?php

declare(strict_types=1);

namespace AbterPhp\Files\Service\Execute;

use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Files\Orm\FileCategoryRepo;
use AbterPhp\Files\Orm\FileRepo as GridRepo;
use AbterPhp\Files\Validation\Factory\File as ValidatorFactory;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Filesystem\Uploader;
use AbterPhp\Framework\Http\Service\Execute\RepoServiceAbstract;
use Cocur\Slugify\Slugify;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Http\Requests\UploadedFile;
use Opulence\Orm\IUnitOfWork;
use Opulence\Orm\OrmException;

class File extends RepoServiceAbstract
{
    const INPUT_NAME_FILE = 'file';

    /** @var Slugify */
    protected $slugify;

    /** @var FileCategoryRepo */
    protected $fileCategoryRepo;

    /** @var Uploader */
    protected $uploader;

    /**
     * File constructor.
     *
     * @param GridRepo         $repo
     * @param ValidatorFactory $validatorFactory
     * @param IUnitOfWork      $unitOfWork
     * @param IEventDispatcher $eventDispatcher
     * @param Slugify          $slugify
     * @param FileCategoryRepo $fileCategoryRepo
     * @param Uploader         $uploader
     */
    public function __construct(
        GridRepo $repo,
        ValidatorFactory $validatorFactory,
        IUnitOfWork $unitOfWork,
        IEventDispatcher $eventDispatcher,
        Slugify $slugify,
        FileCategoryRepo $fileCategoryRepo,
        Uploader $uploader
    ) {
        parent::__construct($repo, $validatorFactory, $unitOfWork, $eventDispatcher);

        $this->slugify          = $slugify;
        $this->fileCategoryRepo = $fileCategoryRepo;
        $this->uploader         = $uploader;
    }

    /**
     * @param string[]       $postData
     * @param UploadedFile[] $fileData
     *
     * @return string
     * @throws OrmException
     */
    public function create(array $postData, array $fileData): string
    {
        $entity = $this->fillEntity($this->createEntity(''), $postData);

        $this->uploadFile($entity, $fileData);

        $this->repo->add($entity);

        $this->commitCreate($entity);

        return $entity->getId();
    }

    /**
     * @param string         $entityId
     * @param string[]       $postData
     * @param UploadedFile[] $fileData
     *
     * @return bool
     * @throws OrmException
     */
    public function update(string $entityId, array $postData, array $fileData): bool
    {
        /** @var Entity $entity */
        $entity = $this->retrieveEntity($entityId);

        $this->fillEntity($entity, $postData);

        if (!empty($fileData)) {
            $this->deleteFile($entity);
            $this->uploadFile($entity, $fileData);
        }

        $this->commitUpdate($entity);

        return true;
    }

    /**
     * @param string $entityId
     *
     * @return bool
     * @throws OrmException
     */
    public function delete(string $entityId): bool
    {
        /** @var Entity $entity */
        $entity = $this->retrieveEntity($entityId);

        $this->deleteFile($entity);

        $this->repo->delete($entity);

        $this->commitDelete($entity);

        return true;
    }

    /**
     * @param Entity $entity
     */
    public function deleteFile(Entity $entity)
    {
        $this->uploader->delete($entity->getFilesystemName());
    }

    /**
     * @param Entity         $entity
     * @param UploadedFile[] $fileData
     */
    public function uploadFile(Entity $entity, array $fileData)
    {
        $paths = $this->uploader->persist($fileData);

        if (!$paths) {
            return;
        }

        $entity->setFilesystemName($paths[static::INPUT_NAME_FILE]);
        $entity->setPublicName($fileData[static::INPUT_NAME_FILE]->getTempFilename());
    }

    /**
     * @param string $entityId
     *
     * @return Entity
     */
    protected function createEntity(string $entityId): IStringerEntity
    {
        $fileCategory = new FileCategory('', '', '', false, []);

        return new Entity($entityId, '', '', '', $fileCategory, null);
    }

    /**
     * @param Entity $entity
     * @param array  $data
     *
     * @return Entity
     * @throws OrmException
     */
    protected function fillEntity(IStringerEntity $entity, array $data): IStringerEntity
    {
        $description = (string)$data['description'];

        /** @var FileCategory $fileCategory */
        $fileCategory = $this->fileCategoryRepo->getById($data['category_id']);

        $entity
            ->setDescription($description)
            ->setCategory($fileCategory);

        if (array_key_exists('file', $data)) {
            $entity
                ->setFilesystemName((string)$data['file'])
                ->setPublicName((string)$data['filename']);
        }

        return $entity;
    }
}
