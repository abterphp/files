<?php

declare(strict_types=1);

namespace AbterPhp\Files\Form\Factory;

use AbterPhp\Admin\Form\Factory\Base;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Files\Orm\FileCategoryRepo;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Component\Option;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

class File extends Base
{
    /** @var FileCategoryRepo */
    protected $fileCategoryRepo;

    /**
     * File constructor.
     *
     * @param ISession         $session
     * @param ITranslator      $translator
     * @param FileCategoryRepo $fileCategoryRepo
     */
    public function __construct(ISession $session, ITranslator $translator, FileCategoryRepo $fileCategoryRepo)
    {
        parent::__construct($session, $translator);

        $this->fileCategoryRepo = $fileCategoryRepo;
    }

    /**
     * @param string       $action
     * @param string       $method
     * @param string       $showUrl
     * @param IEntity|null $entity
     *
     * @return IForm
     */
    public function create(string $action, string $method, string $showUrl, ?IEntity $entity = null): IForm
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $this->createForm($action, $method, true)
            ->addDefaultElements()
            ->addFile()
            ->addDescription($entity)
            ->addFileCategory($entity)
            ->addDefaultButtons($showUrl);

        $form = $this->form;

        $this->form = null;

        return $form;
    }

    /**
     * @return $this
     */
    protected function addFile(): File
    {
        $input = new Input('file', 'file', '', [], [Html5::ATTR_TYPE => Input::TYPE_FILE]);
        $label = new Label('file', 'files:file');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDescription(Entity $entity): File
    {
        $input = new Textarea('description', 'description', $entity->getDescription());
        $label = new Label('description', 'files:fileDescription');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return File
     * @throws \Opulence\Orm\OrmException
     */
    protected function addFileCategory(Entity $entity): File
    {
        $allFileCategories = $this->fileCategoryRepo->getAll();
        $fileCategoryId    = $entity->getCategory()->getId();

        $options = $this->createFileCategoryOptions($allFileCategories, $fileCategoryId);

        $this->form[] = new FormGroup(
            $this->createFileCategorySelect($options),
            $this->createFileCategoryLabel()
        );

        return $this;
    }

    /**
     * @param FileCategory[] $allFileCategories
     * @param string         $fileCategoryId
     *
     * @return array
     */
    protected function createFileCategoryOptions(array $allFileCategories, string $fileCategoryId): array
    {
        $options = [];
        foreach ($allFileCategories as $fileCategory) {
            $isSelected = $fileCategory->getId() === $fileCategoryId;
            $options[]  = new Option($fileCategory->getId(), $fileCategory->getName(), $isSelected);
        }

        return $options;
    }

    /**
     * @param Option[] $options
     *
     * @return Select
     */
    protected function createFileCategorySelect(array $options): Select
    {
        $attributes = [
            Html5::ATTR_SIZE => $this->getMultiSelectSize(
                count($options),
                static::MULTISELECT_MIN_SIZE,
                static::MULTISELECT_MAX_SIZE
            ),
        ];

        $select = new Select('category_id', 'category_id', [], $attributes);

        foreach ($options as $option) {
            $select[] = $option;
        }

        return $select;
    }

    /**
     * @return Label
     */
    protected function createFileCategoryLabel(): Label
    {
        return new Label('file_category_id', 'files:fileCategory');
    }

    /**
     * @param int $optionCount
     * @param int $minSize
     * @param int $maxSize
     *
     * @return int
     */
    protected function getMultiSelectSize(int $optionCount, int $minSize, int $maxSize): int
    {
        return (int)max(min($optionCount, $maxSize), $minSize);
    }
}
