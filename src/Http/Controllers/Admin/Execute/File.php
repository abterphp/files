<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Admin\Execute;

use AbterPhp\Admin\Http\Controllers\Admin\ExecuteAbstract;
use AbterPhp\Files\Service\Execute\File as RepoService;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

class File extends ExecuteAbstract
{
    const ENTITY_SINGULAR = 'file';
    const ENTITY_PLURAL   = 'files';

    const ENTITY_TITLE_SINGULAR = 'files:file';
    const ENTITY_TITLE_PLURAL   = 'files:files';

    /**
     * File constructor.
     *
     * @param FlashService    $flashService
     * @param ITranslator     $translator
     * @param UrlGenerator    $urlGenerator
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     * @param ISession        $session
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        LoggerInterface $logger,
        RepoService $repoService,
        ISession $session
    ) {
        parent::__construct(
            $flashService,
            $translator,
            $urlGenerator,
            $logger,
            $repoService,
            $session
        );
    }
}
