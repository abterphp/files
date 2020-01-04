<?php

declare(strict_types=1);

use AbterPhp\Admin\Config\Routes as RoutesConfig;
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
        $router->group(
            [
                'path'       => RoutesConfig::getAdminBasePath(),
                'middleware' => [
                    Authentication::class,
                ],
            ],
            function (Router $router) {
                $entities = [
                    'file-categories' => 'FileCategory',
                    'file-downloads'  => 'FileDownload',
                    'files'          => 'File',
                ];

                foreach ($entities as $route => $controllerName) {
                    $path = strtolower($controllerName);

                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Grid\File::show() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Grid\FileCategory::show() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Grid\FileDownload::show() */
                    $router->get(
                        "/${path}",
                        "Admin\Grid\\${controllerName}@show",
                        [
                            RoutesConstant::OPTION_NAME       => "${route}",
                            RoutesConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::READ,
                                    ]
                                ),
                                LastGridPage::class,
                            ],
                        ]
                    );
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Form\File::new() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Form\FileCategory::new() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Form\FileDownload::new() */
                    $router->get(
                        "/${path}/new",
                        "Admin\Form\\${controllerName}@new",
                        [
                            RoutesConstant::OPTION_NAME       => "${route}-new",
                            RoutesConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\File::create() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\FileCategory::create() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\FileDownload::create() */
                    $router->post(
                        "/${path}/new",
                        "Admin\Execute\\${controllerName}@create",
                        [
                            RoutesConstant::OPTION_NAME       => "${route}-create",
                            RoutesConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Form\File::edit() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Form\FileCategory::edit() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Form\FileDownload::edit() */
                    $router->get(
                        "/${path}/:entityId/edit",
                        "Admin\Form\\${controllerName}@edit",
                        [
                            RoutesConstant::OPTION_NAME       => "${route}-edit",
                            RoutesConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\File::update() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\FileCategory::update() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\FileDownload::update() */
                    $router->put(
                        "/${path}/:entityId/edit",
                        "Admin\Execute\\${controllerName}@update",
                        [
                            RoutesConstant::OPTION_NAME       => "${route}-update",
                            RoutesConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\File::delete() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\FileCategory::delete() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\FileDownload::delete() */
                    $router->get(
                        "/${path}/:entityId/delete",
                        "Admin\Execute\\${controllerName}@delete",
                        [
                            RoutesConstant::OPTION_NAME       => "${route}-delete",
                            RoutesConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );
                }
            }
        );
    }
);
