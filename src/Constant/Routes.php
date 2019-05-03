<?php

declare(strict_types=1);

namespace AbterPhp\Files\Constant;

class Routes
{
    const ROUTE_API_CSV      = 'api-csv';
    const ROUTE_API_DOWNLOAD = 'api-download';
    const ROUTE_PUBLIC_FILE  = 'public-file';

    const PATH_API_CSV      = '/files/csv';
    const PATH_API_DOWNLOAD = '/files/:filesystemName';
    const PATH_FILE         = '/file/:filesystemName';

    const ROUTE_FILE_CATEGORIES        = 'filecategories';
    const ROUTE_FILE_CATEGORIES_NEW    = 'filecategories-new';
    const ROUTE_FILE_CATEGORIES_EDIT   = 'filecategories-edit';
    const ROUTE_FILE_CATEGORIES_DELETE = 'filecategories-delete';
    const PATH_FILE_CATEGORIES         = '/filecategory';
    const PATH_FILE_CATEGORIES_NEW     = '/filecategory/new';
    const PATH_FILE_CATEGORIES_EDIT    = '/filecategory/:id/edit';
    const PATH_FILE_CATEGORIES_DELETE  = '/filecategory/:id/delete';

    const ROUTE_FILES          = 'files';
    const ROUTE_FILES_NEW      = 'files-new';
    const ROUTE_FILES_EDIT     = 'files-edit';
    const ROUTE_FILES_DELETE   = 'files-delete';
    const ROUTE_FILES_DOWNLOAD = 'files-download';
    const PATH_FILES           = '/file';
    const PATH_FILES_NEW       = '/file/new';
    const PATH_FILES_EDIT      = '/file/:id/edit';
    const PATH_FILES_DELETE    = '/file/:id/delete';
    const PATH_FILES_DOWNLOAD  = '/file/:id';

    const ROUTE_FILE_DOWNLOADS = 'filedownloads';
    const PATH_FILE_DOWNLOADS  = '/filedownload';
}
