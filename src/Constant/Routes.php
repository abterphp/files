<?php

declare(strict_types=1);

namespace AbterPhp\Files\Constant;

use AbterPhp\Framework\Constant\Routes as FrameworkConstant;

class Routes extends FrameworkConstant
{
    const ROUTE_API_CSV      = 'api-csv';
    const ROUTE_API_DOWNLOAD = 'api-download';
    const ROUTE_PUBLIC_FILE  = 'public-file';

    const PATH_API_CSV      = '/files/csv';
    const PATH_API_DOWNLOAD = '/files/:filesystemName';
    const PATH_FILE         = '/file/:filesystemName';

    const ROUTE_FILE_CATEGORIES        = 'file-categories';
    const ROUTE_FILE_CATEGORIES_NEW    = 'file-categories-new';
    const ROUTE_FILE_CATEGORIES_EDIT   = 'file-categories-edit';
    const ROUTE_FILE_CATEGORIES_DELETE = 'file-categories-delete';
    const PATH_FILE_CATEGORIES         = '/file-categories';
    const PATH_FILE_CATEGORIES_NEW     = '/file-categories/new';
    const PATH_FILE_CATEGORIES_EDIT    = '/file-categories/:entityId/edit';
    const PATH_FILE_CATEGORIES_DELETE  = '/file-categories/:entityId/delete';

    const ROUTE_FILES          = 'files';
    const ROUTE_FILES_NEW      = 'files-new';
    const ROUTE_FILES_EDIT     = 'files-edit';
    const ROUTE_FILES_DELETE   = 'files-delete';
    const ROUTE_FILES_DOWNLOAD = 'files-download';
    const PATH_FILES           = '/files';
    const PATH_FILES_NEW       = '/files/new';
    const PATH_FILES_EDIT      = '/files/:entityId/edit';
    const PATH_FILES_DELETE    = '/files/:entityId/delete';
    const PATH_FILES_DOWNLOAD  = '/files/:entityId';

    const ROUTE_FILE_DOWNLOADS = 'file-downloads';
    const PATH_FILE_DOWNLOADS  = '/file-downloads';
}
