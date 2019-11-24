<?php

declare(strict_types=1);

use AbterPhp\Admin\Config\Routes as RoutesConfig;
use AbterPhp\Admin\Http\Middleware\Api;
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
                'path' => RoutesConfig::getApiBasePath(),
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
                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileCategory::get() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\File::get() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileDownload::get() */
                    $router->get(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@get"
                    );

                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileCategory::list() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\File::list() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileDownload::list() */
                    $router->get(
                        "/${route}",
                        "Api\\${controllerName}@list"
                    );

                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileCategory::create() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\File::create() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileDownload::create() */
                    $router->post(
                        "/${route}",
                        "Api\\${controllerName}@create"
                    );

                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileCategory::update() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\File::update() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileDownload::update() */
                    $router->put(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@update"
                    );

                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileCategory::delete() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\File::delete() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileDownload::delete() */
                    $router->delete(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@delete"
                    );
                }
            }
        );
    }
);
