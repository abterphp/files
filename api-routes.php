<?php

declare(strict_types=1);

use AbterPhp\Admin\Http\Middleware\Api;
use AbterPhp\Admin\Http\Middleware\Authentication;
use AbterPhp\Admin\Http\Middleware\Authorization;
use AbterPhp\Admin\Http\Middleware\LastGridPage;
use AbterPhp\Files\Constant\Routes;
use AbterPhp\Framework\Authorization\Constant\Role;
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

        $router->group(
            [
                'path'       => PATH_ADMIN,
                'middleware' => [
                    Authentication::class,
                ],
            ],
            function (Router $router) {
            }
        );
    }
);
