<?php

declare(strict_types=1);

namespace AbterPhp\Files\Constant;

use AbterPhp\Framework\Constant\Route as FrameworkRoute;

class Route extends FrameworkRoute
{
    public const PUBLIC_FILE = 'public-file';

    public const FILE_CATEGORIES_LIST   = 'file-categories-list';
    public const FILE_CATEGORIES_NEW    = 'file-categories-new';
    public const FILE_CATEGORIES_CREATE = 'file-categories-create';
    public const FILE_CATEGORIES_EDIT   = 'file-categories-edit';
    public const FILE_CATEGORIES_UPDATE = 'file-categories-update';
    public const FILE_CATEGORIES_DELETE = 'file-categories-delete';
    public const FILE_CATEGORIES_BASE   = 'file-categories-base';
    public const FILE_CATEGORIES_ENTITY = 'file-categories-entity';

    public const FILES_LIST     = 'files-list';
    public const FILES_NEW      = 'files-new';
    public const FILES_CREATE   = 'files-create';
    public const FILES_EDIT     = 'files-edit';
    public const FILES_UPDATE   = 'files-update';
    public const FILES_DELETE   = 'files-delete';
    public const FILES_BASE     = 'files-base';
    public const FILES_ENTITY   = 'files-entity';
    public const FILES_DOWNLOAD = 'files-download';

    public const FILE_DOWNLOADS_LIST = 'file-downloads-list';
}
