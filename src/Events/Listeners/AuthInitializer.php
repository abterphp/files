<?php

declare(strict_types=1);

namespace AbterPhp\Files\Events\Listeners;

use AbterPhp\Files\Authorization\FileCategoryProvider as AuthProvider;
use AbterPhp\Framework\Events\AuthReady;

class AuthInitializer
{
    /** @var AuthProvider */
    protected $authProvider;

    /**
     * AuthRegistrar constructor.
     *
     * @param AuthProvider $authProvider
     */
    public function __construct(AuthProvider $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    /**
     * @param AuthReady $event
     */
    public function handle(AuthReady $event)
    {
        $event->getAdapter()->registerAdapter($this->authProvider);
    }
}
