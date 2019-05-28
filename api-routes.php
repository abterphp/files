<?php

declare(strict_types=1);

use AbterPhp\Admin\Http\Middleware\Api;
use AbterPhp\Files\Constant\Routes;
use Opulence\Routing\Router;

/**
 * ----------------------------------------------------------
 * Create all of the routes for the HTTP kernel
 * ----------------------------------------------------------
 *
 * @var Router $router
 */
$router->group(
    ['controllerNamespace' => 'AbterPhp\Files\Http\Controllers'],
    function (Router $router) {
        $router->group(
            [
                'path'       => PATH_API,
                'middleware' => [
                    Api::class,
                ],
            ],
            function (Router $router) {
                $entities = [
                    'filecategories' => 'FileCategory',
                    'filedownloads'  => 'FileDownload',
                    'files'          => 'File',
                ];

                foreach ($entities as $route => $controllerName) {
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\FileCategory::create() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\File::create() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\FileDownload::create() */
                    $router->post(
                        "/${route}",
                        "Api\\${controllerName}@create"
                    );

                    /** @see \AbterPhp\Admin\Http\Controllers\Api\FileCategory::update() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\File::update() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\FileDownload::update() */
                    $router->put(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@update"
                    );

                    /** @see \AbterPhp\Admin\Http\Controllers\Api\FileCategory::delete() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\File::delete() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\FileDownload::delete() */
                    $router->delete(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@delete"
                    );
                }

                /** @see \AbterPhp\Files\Http\Controllers\Api\File\Csv::csv() */
                $router->get(
                    Routes::PATH_API_CSV,
                    'Api\File\Csv@csv',
                    [OPTION_NAME => Routes::ROUTE_API_CSV]
                );
                /** @see \AbterPhp\Files\Http\Controllers\Api\File\Download::download() */
                $router->get(
                    Routes::PATH_API_DOWNLOAD,
                    'Api\File\Download@download',
                    [OPTION_NAME => Routes::ROUTE_API_DOWNLOAD]
                );
            }
        );
    }
);
