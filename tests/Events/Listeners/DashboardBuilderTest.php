<?php

declare(strict_types=1);

namespace AbterPhp\Files\Events\Listeners;

use AbterPhp\Framework\Dashboard\Dashboard;
use AbterPhp\Framework\Events\DashboardReady;
use PHPUnit\Framework\TestCase;

class DashboardBuilderTest extends TestCase
{
    /** @var DashboardBuilder - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new DashboardBuilder();
    }

    public function testHandle()
    {
        $dashboardMock = $this->createMock(Dashboard::class);
        $dashboardMock->expects($this->atLeastOnce())->method('offsetSet');

        $event = new DashboardReady($dashboardMock);

        $this->sut->handle($event);
    }
}
