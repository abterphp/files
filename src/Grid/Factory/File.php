<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory;

use AbterPhp\Files\Constant\Routes;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Grid\Factory\Table\File as Table;
use AbterPhp\Files\Grid\Filters\File as Filters;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Grid\Action\Action;
use AbterPhp\Framework\Grid\Component\Actions;
use AbterPhp\Framework\Grid\Factory\BaseFactory;
use AbterPhp\Framework\Grid\Factory\GridFactory;
use AbterPhp\Framework\Grid\Factory\PaginationFactory as PaginationFactory;
use AbterPhp\Framework\Helper\DateHelper;
use AbterPhp\Framework\Html\Component;
use Opulence\Routing\Urls\UrlGenerator;

class File extends BaseFactory
{
    const GROUP_ID          = 'file-id';
    const GROUP_FILENAME    = 'file-filename';
    const GROUP_CATEGORY    = 'file-category';
    const GROUP_DESCRIPTION = 'file-description';
    const GROUP_UPLOADED_AT = 'file-uploaded-at';

    const GETTER_ID          = 'getId';
    const GETTER_PUBLIC_NAME = 'getPublicName';
    const GETTER_CATEGORY    = 'getCategory';
    const GETTER_DESCRIPTION = 'getDescription';

    const LABEL_DOWNLOAD = 'files:download';

    /**
     * File constructor.
     *
     * @param UrlGenerator      $urlGenerator
     * @param PaginationFactory $paginationFactory
     * @param Table             $tableFactory
     * @param GridFactory       $gridFactory
     * @param Filters           $filters
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        PaginationFactory $paginationFactory,
        Table $tableFactory,
        GridFactory $gridFactory,
        Filters $filters
    ) {
        parent::__construct($urlGenerator, $paginationFactory, $tableFactory, $gridFactory, $filters);
    }

    /**
     * @return array
     */
    public function getGetters(): array
    {
        return [
            static::GROUP_ID          => static::GETTER_ID,
            static::GROUP_FILENAME    => static::GETTER_PUBLIC_NAME,
            static::GROUP_CATEGORY    => static::GETTER_CATEGORY,
            static::GROUP_DESCRIPTION => static::GETTER_DESCRIPTION,
            /** @see File::getUploadedAt */
            static::GROUP_UPLOADED_AT => [$this, 'getUploadedAt'],
        ];
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public function getUploadedAt(Entity $entity): string
    {
        return DateHelper::format($entity->getUploadedAt());
    }

    /**
     * @return Actions
     */
    protected function getRowActions(): Actions
    {
        $attributeCallbacks = $this->getAttributeCallbacks();
        $downloadCallbacks  = $this->getDownloadCallbacks();

        $downloadAttributes = [
            Html5::ATTR_HREF  => Routes::ROUTE_FILES_DOWNLOAD,
        ];
        $editAttributes     = [
            Html5::ATTR_HREF  => Routes::ROUTE_FILES_EDIT,
        ];
        $deleteAttributes   = [
            Html5::ATTR_HREF  => Routes::ROUTE_FILES_DELETE,
        ];

        $cellActions   = new Actions();
        $cellActions[] = new Action(
            static::LABEL_DOWNLOAD,
            $this->downloadIntents,
            $downloadAttributes,
            $downloadCallbacks,
            Html5::TAG_A
        );
        $cellActions[] = new Action(
            static::LABEL_EDIT,
            $this->editIntents,
            $editAttributes,
            $attributeCallbacks,
            Html5::TAG_A
        );
        $cellActions[] = new Action(
            static::LABEL_DELETE,
            $this->deleteIntents,
            $deleteAttributes,
            $attributeCallbacks,
            Html5::TAG_A
        );

        return $cellActions;
    }

    /**
     * @return callable[]
     */
    protected function getAttributeCallbacks(): array
    {
        $attributeCallbacks = parent::getAttributeCallbacks();

        $attributeCallbacks[Html5::ATTR_CLASS] = function ($attribute, Entity $entity) {
            return $entity->isWritable() ? $attribute : Component::INTENT_HIDDEN;
        };

        return $attributeCallbacks;
    }

    /**
     * @return callable[]
     */
    protected function getDownloadCallbacks(): array
    {
        $urlGenerator = $this->urlGenerator;

        $closure = function ($attribute, Entity $entity) use ($urlGenerator) {
            try {
                return $urlGenerator->createFromName($attribute, $entity->getFilesystemName());
            } catch (\Exception $e) {
                return '';
            }
        };

        $attributeCallbacks = [
            Html5::ATTR_HREF => $closure,
        ];

        return $attributeCallbacks;
    }
}
