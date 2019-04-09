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
                'path'       => PATH_ADMIN,
                'middleware' => [
                    Authentication::class,
                ],
            ],
            function (Router $router) {
                $entities = [
                    'filecategories' => 'FileCategory',
                    'filedownloads'  => 'FileDownload',
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
                            OPTION_NAME       => "${route}",
                            OPTION_MIDDLEWARE => [
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
                            OPTION_NAME       => "${route}-new",
                            OPTION_MIDDLEWARE => [
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
                            OPTION_NAME       => "${route}-create",
                            OPTION_MIDDLEWARE => [
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
                            OPTION_NAME       => "${route}-edit",
                            OPTION_MIDDLEWARE => [
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
                            OPTION_NAME       => "${route}-update",
                            OPTION_MIDDLEWARE => [
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
                            OPTION_NAME       => "${route}-delete",
                            OPTION_MIDDLEWARE => [
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

                /** @see \AbterPhp\Files\Http\Controllers\Api\File\Download::download() */
                $router->get(
                    Routes::PATH_API_DOWNLOAD,
                    'Api\File\Download@download',
                    [
                        OPTION_NAME       => Routes::ROUTE_FILES_DOWNLOAD,
                        OPTION_MIDDLEWARE => [
                            Authorization::withParameters(
                                [
                                    Authorization::RESOURCE => 'files',
                                    Authorization::ROLE     => Role::READ,
                                ]
                            ),
                        ],
                    ]
                );

                /** @see \AbterPhp\Files\Http\Controllers\Api\File\Csv::csv() */
                $router->get(
                    Routes::PATH_API_DOWNLOAD,
                    'Api\File\Download@download',
                    [
                        OPTION_NAME       => Routes::ROUTE_FILES_DOWNLOAD,
                        OPTION_MIDDLEWARE => [
                            Authorization::withParameters(
                                [
                                    Authorization::RESOURCE => 'files',
                                    Authorization::ROLE     => Role::READ,
                                ]
                            ),
                        ],
                    ]
                );
            }
        );
    }
);
