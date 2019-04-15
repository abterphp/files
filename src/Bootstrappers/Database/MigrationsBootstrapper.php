<?php

declare(strict_types=1);

namespace AbterPhp\Files\Bootstrappers\Database;

use AbterPhp\Admin\Bootstrappers\Database\MigrationsBootstrapper as AdminBootstrapper;
use AbterPhp\Website\Databases\Migrations\Init;
use Opulence\Databases\IConnection;
use Opulence\Ioc\IContainer;

class MigrationsBootstrapper extends AdminBootstrapper
{
    const MODULE_KEY = 'AbterPhp\\Files';

    /**
     * @return array
     */
    public function getBindings(): array
    {
        return [
            Init::class,
        ];
    }

    /**
     * @param IContainer $container
     *
     * @throws \Opulence\Ioc\IocException
     */
    public function registerBindings(IContainer $container)
    {
        $migrationsPath = $this->getMigrationPath();
        $driver         = $this->getDriver();

        /** @var IConnection $connection */
        $connection = $container->resolve(IConnection::class);

        $migration = new Init($connection, $migrationsPath, $driver);

        $container->bindInstance(Init::class, $migration);
    }
}
