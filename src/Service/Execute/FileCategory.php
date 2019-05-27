<?php

declare(strict_types=1);

namespace AbterPhp\Files\Service\Execute;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Files\Orm\FileCategoryRepo as GridRepo;
use AbterPhp\Files\Validation\Factory\FileCategory as ValidatorFactory;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Http\Service\Execute\RepoServiceAbstract;
use Cocur\Slugify\Slugify;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Http\Requests\UploadedFile;
use Opulence\Orm\IUnitOfWork;

class FileCategory extends RepoServiceAbstract
{
    /** @var Slugify */
    protected $slugify;

    /**
     * FileCategory constructor.
     *
     * @param GridRepo         $repo
     * @param ValidatorFactory $validatorFactory
     * @param IUnitOfWork      $unitOfWork
     * @param IEventDispatcher $eventDispatcher
     * @param Slugify          $slugify
     */
    public function __construct(
        GridRepo $repo,
        ValidatorFactory $validatorFactory,
        IUnitOfWork $unitOfWork,
        IEventDispatcher $eventDispatcher,
        Slugify $slugify
    ) {
        parent::__construct($repo, $validatorFactory, $unitOfWork, $eventDispatcher);

        $this->slugify = $slugify;
    }

    /**
     * @param string $entityId
     *
     * @return Entity
     */
    public function createEntity(string $entityId): IStringerEntity
    {
        $entity = new Entity($entityId, '', '', false);

        return $entity;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Entity         $entity
     * @param array          $postData
     * @param UploadedFile[] $fileData
     *
     * @return Entity
     */
    protected function fillEntity(IStringerEntity $entity, array $postData, array $fileData): IStringerEntity
    {
        $name = isset($postData['name']) ? (string)$postData['name'] : '';

        $identifier = (string)$postData['identifier'];
        if (empty($identifier)) {
            $identifier = $name;
        }
        $identifier = $this->slugify->slugify($identifier);

        $userGroups = [];
        if (array_key_exists('user_group_ids', $postData)) {
            foreach ($postData['user_group_ids'] as $id) {
                $userGroups[] = new UserGroup((string)$id, '', '');
            }
        }

        $isPublic = isset($postData['is_public']) ? (bool)$postData['is_public'] : false;

        $entity
            ->setName($name)
            ->setIdentifier($identifier)
            ->setUserGroups($userGroups)
            ->setIsPublic($isPublic);

        return $entity;
    }
}
