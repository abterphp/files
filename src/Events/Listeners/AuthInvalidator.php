<?php

declare(strict_types=1);

namespace AbterPhp\Files\Events\Listeners;

use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Framework\Authorization\CacheManager;
use AbterPhp\Framework\Events\EntityChange;

class AuthInvalidator
{
    /** @var CacheManager */
    protected $cacheManager;

    /**
     * AuthInvalidator constructor.
     *
     * @param CacheManager $cacheManager
     */
    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param EntityChange $event
     */
    public function handle(EntityChange $event)
    {
        switch ($event->getEntityName()) {
            case FileCategory::class:
                $this->cacheManager->clearAll();
                break;
        }
    }
}
