<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Admin\Form;

use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Files\Form\Factory\File as FormFactory;
use AbterPhp\Files\Orm\FileRepo as Repo;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Http\Controllers\Admin\FormAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

class File extends FormAbstract
{
    const ENTITY_PLURAL   = 'files';
    const ENTITY_SINGULAR = 'file';

    const ENTITY_TITLE_SINGULAR = 'files:file';
    const ENTITY_TITLE_PLURAL   = 'files:files';

    /** @var string */
    protected $resource = 'files';

    /**
     * File constructor.
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
    protected function createEntity(string $entityId): IStringerEntity
    {
        $fileCategory = new FileCategory('', '', '', false, []);

        return new Entity($entityId, '', '', '', '', $fileCategory, null);
    }
}
