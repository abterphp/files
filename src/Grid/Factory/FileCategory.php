<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory;

use AbterPhp\Admin\Grid\Factory\BaseFactory;
use AbterPhp\Admin\Grid\Factory\GridFactory;
use AbterPhp\Admin\Grid\Factory\PaginationFactory;
use AbterPhp\Files\Constant\Route;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Files\Grid\Factory\Table\Header\FileCategory as HeaderFactory;
use AbterPhp\Files\Grid\Factory\Table\FileCategory as TableFactory;
use AbterPhp\Files\Grid\Filters\FileCategory as Filters;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Grid\Action\Action;
use AbterPhp\Framework\Grid\Component\Actions;
use Opulence\Routing\Urls\UrlGenerator;

class FileCategory extends BaseFactory
{
    private const GETTER_IDENTIFIER = 'getIdentifier';
    private const GETTER_NAME       = 'getName';

    /**
     * FileCategory constructor.
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
            HeaderFactory::GROUP_NAME       => static::GETTER_NAME,
            HeaderFactory::GROUP_IS_PUBLIC  => [$this, 'getIsPublic'],
            HeaderFactory::GROUP_IDENTIFIER => static::GETTER_IDENTIFIER,
        ];
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public function getIsPublic(Entity $entity): string
    {
        $expr = $entity->isPublic() ? 'framework:yes' : 'framework:no';

        return $expr;
    }

    /**
     * @return Actions
     */
    protected function getRowActions(): Actions
    {
        $attributeCallbacks = $this->getAttributeCallbacks();

        $editAttributes = [
            Html5::ATTR_HREF => Route::FILE_CATEGORIES_EDIT,
        ];

        $deleteAttributes = [
            Html5::ATTR_HREF => Route::FILE_CATEGORIES_DELETE,
        ];

        $cellActions   = new Actions();
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
}
