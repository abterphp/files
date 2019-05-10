<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory;

use AbterPhp\Files\Constant\Routes;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Files\Grid\Factory\Table\FileCategory as Table;
use AbterPhp\Files\Grid\Filters\FileCategory as Filters;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Grid\Action\Action;
use AbterPhp\Framework\Grid\Component\Actions;
use AbterPhp\Framework\Grid\Factory\BaseFactory;
use AbterPhp\Framework\Grid\Factory\GridFactory;
use AbterPhp\Framework\Grid\Factory\PaginationFactory as PaginationFactory;
use Opulence\Routing\Urls\UrlGenerator;

class FileCategory extends BaseFactory
{
    const GROUP_IDENTIFIER = 'fileCategory-identifier';
    const GROUP_NAME       = 'fileCategory-name';
    const GROUP_IS_PUBLIC  = 'fileCategory-is-public';

    const GETTER_IDENTIFIER = 'getIdentifier';
    const GETTER_NAME       = 'getName';
    const GETTER_IS_PUBLIC  = 'isPublic';

    /**
     * FileCategory constructor.
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
            static::GROUP_IDENTIFIER => static::GETTER_IDENTIFIER,
            static::GROUP_NAME       => static::GETTER_NAME,
            static::GROUP_IS_PUBLIC  => [$this, 'getIsPublic'],
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
            Html5::ATTR_HREF  => Routes::ROUTE_FILE_CATEGORIES_EDIT,
        ];

        $deleteAttributes = [
            Html5::ATTR_HREF  => Routes::ROUTE_FILE_CATEGORIES_DELETE,
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
