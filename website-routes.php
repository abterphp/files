<?php

declare(strict_types=1);

use AbterPhp\Admin\Http\Middleware\Api;
use AbterPhp\Admin\Http\Middleware\Authentication;
use AbterPhp\Admin\Http\Middleware\Authorization;
use AbterPhp\Admin\Http\Middleware\LastGridPage;
use AbterPhp\Files\Constant\Routes as RoutesConstant;
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
        /** @see \AbterPhp\Files\Http\Controllers\Website\File::download() */
        $router->get(
            RoutesConstant::PATH_FILE,
            'Website\File@download',
            [RoutesConstant::OPTION_NAME => RoutesConstant::ROUTE_PUBLIC_FILE]
        );
    }
);
