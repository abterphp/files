<?php

declare(strict_types=1);

namespace AbterPhp\Files\Bootstrappers\Validation;

use AbterPhp\Files\Validation\Factory\File;
use AbterPhp\Files\Validation\Factory\FileCategory;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;

/**
 * Defines the validator bootstrapper
 */
class ValidatorBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /**
     * @inheritdoc
     */
    public function getBindings(): array
    {
        return [
            FileCategory::class,
            File::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        parent::registerBindings($container);
    }
}
