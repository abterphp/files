<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Admin\Form;

use AbterPhp\Admin\Http\Controllers\Admin\FormAbstract;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Files\Form\Factory\FileCategory as FormFactory;
use AbterPhp\Files\Orm\FileCategoryRepo as Repo;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

class FileCategory extends FormAbstract
{
    const ENTITY_PLURAL   = 'fileCategories';
    const ENTITY_SINGULAR = 'fileCategory';

    const ENTITY_TITLE_SINGULAR = 'files:fileCategory';
    const ENTITY_TITLE_PLURAL   = 'files:fileCategories';

    /** @var string */
    protected $resource = 'file_categories';

    /**
     * FileCategory constructor.
     *
     * @param FlashService     $flashService
     * @param ITranslator      $translator
     * @param UrlGenerator     $urlGenerator
     * @param LoggerInterface  $logger
     * @param Repo             $repo
     * @param ISession         $session
     * @param FormFactory      $formFactory
     * @param IEventDispatcher $eventDispatcher
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        LoggerInterface $logger,
        Repo $repo,
        ISession $session,
        FormFactory $formFactory,
        IEventDispatcher $eventDispatcher
    ) {
        parent::__construct(
            $flashService,
            $translator,
            $urlGenerator,
            $logger,
            $repo,
            $session,
            $formFactory,
            $eventDispatcher
        );
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
}
