<?php

declare(strict_types=1);

namespace AbterPhp\Files\Service\RepoGrid;

use AbterPhp\Admin\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Files\Grid\Factory\FileDownload as GridFactory;
use AbterPhp\Files\Orm\FileDownloadRepo as Repo;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use Casbin\Enforcer;

class FileDownload extends RepoGridAbstract
{
    /**
     * FileDownload constructor.
     *
     * @param Enforcer    $enforcer
     * @param Repo        $repo
     * @param FoundRows   $foundRows
     * @param GridFactory $gridFactory
     */
    public function __construct(Enforcer $enforcer, Repo $repo, FoundRows $foundRows, GridFactory $gridFactory)
    {
        parent::__construct($enforcer, $repo, $foundRows, $gridFactory);
    }
}
