<?php

declare(strict_types=1);

namespace AbterPhp\Files\Events\Listeners;

use AbterPhp\Framework\Events\AuthReady;
use AbterPhp\Files\Authorization\FileCategoryProvider;

class AuthInitializer
{
    /** @var FileCategoryProvider */
    protected $fileCategoryProvider;

    /**
     * AuthRegistrar constructor.
     *
     * @param FileCategoryProvider $authProvider
     */
    public function __construct(FileCategoryProvider $authProvider)
    {
        $this->fileCategoryProvider = $authProvider;
    }

    /**
     * @param AuthReady $event
     */
    public function handle(AuthReady $event)
    {
        $event->getAdapter()->registerAdapter($this->fileCategoryProvider);
    }
}
