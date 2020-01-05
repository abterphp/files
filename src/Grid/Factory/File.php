<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory;

use AbterPhp\Admin\Grid\Factory\BaseFactory;
use AbterPhp\Admin\Grid\Factory\GridFactory;
use AbterPhp\Admin\Grid\Factory\PaginationFactory;
use AbterPhp\Files\Constant\Route;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Grid\Factory\Table\Header\File as HeaderFactory;
use AbterPhp\Files\Grid\Factory\Table\File as TableFactory;
use AbterPhp\Files\Grid\Filters\File as Filters;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Grid\Action\Action;
use AbterPhp\Framework\Grid\Component\Actions;
use AbterPhp\Framework\Helper\DateHelper;
use AbterPhp\Framework\Html\Component;
use Opulence\Routing\Urls\UrlGenerator;

class File extends BaseFactory
{
    private const GETTER_PUBLIC_NAME = 'getPublicName';
    private const GETTER_CATEGORY    = 'getCategory';
    private const GETTER_DESCRIPTION = 'getDescription';

    private const LABEL_DOWNLOAD = 'files:download';

    /**
     * File constructor.
     *
     * @param UrlGenerator      $urlGenerator
     * @param PaginationFactory $paginationFactory
     * @param TableFactory      $tableFactory
     * @param GridFactory       $gridFactory
     * @param Filters           $filters
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        PaginationFactory $paginationFactory,
        TableFactory $tableFactory,
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
            HeaderFactory::GROUP_FILENAME    => static::GETTER_PUBLIC_NAME,
            HeaderFactory::GROUP_CATEGORY    => static::GETTER_CATEGORY,
            HeaderFactory::GROUP_DESCRIPTION => static::GETTER_DESCRIPTION,
            /** @see File::getUploadedAt */
            HeaderFactory::GROUP_UPLOADED_AT => [$this, 'getUploadedAt'],
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
            Html5::ATTR_HREF  => Route::PUBLIC_FILE,
        ];
        $editAttributes     = [
            Html5::ATTR_HREF  => Route::FILES_EDIT,
        ];
        $deleteAttributes   = [
            Html5::ATTR_HREF  => Route::FILES_DELETE,
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
                // @phan-suppress-next-line PhanTypeMismatchArgument
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
