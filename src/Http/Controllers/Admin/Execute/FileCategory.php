<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Admin\Execute;

use AbterPhp\Files\Service\Execute\FileCategory as RepoService;
use AbterPhp\Framework\Http\Controllers\Admin\ExecuteAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

class FileCategory extends ExecuteAbstract
{
    const ENTITY_SINGULAR = 'fileCategory';
    const ENTITY_PLURAL   = 'fileCategories';

    const ENTITY_TITLE_SINGULAR = 'files:fileCategory';
    const ENTITY_TITLE_PLURAL   = 'files:fileCategories';

    /**
     * FileCategory constructor.
     *
     * @param FlashService    $flashService
     * @param ITranslator     $translator
     * @param UrlGenerator    $urlGenerator
     * @param RepoService     $repoService
     * @param ISession        $session
     * @param LoggerInterface $logger
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        RepoService $repoService,
        ISession $session,
        LoggerInterface $logger
    ) {
        parent::__construct(
            $flashService,
            $translator,
            $urlGenerator,
            $repoService,
            $session,
            $logger
        );
    }
}
