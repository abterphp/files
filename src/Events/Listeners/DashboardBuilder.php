<?php

declare(strict_types=1);

namespace AbterPhp\Files\Events\Listeners;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Events\DashboardReady;
use AbterPhp\Framework\Html\Component;

class DashboardBuilder
{
    const CONTENT = 'Insert dashboard component for Files module.';
    /**
     * @param DashboardReady $event
     */
    public function handle(DashboardReady $event)
    {
        $event->getDashboard()[] = new Component(static::CONTENT, [], [], Html5::TAG_P);
    }
}
