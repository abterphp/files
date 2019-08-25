<?php

declare(strict_types=1);

namespace AbterPhp\Files\Events\Listeners;

use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Html\Component\ButtonFactory;
use AbterPhp\Framework\Navigation\Navigation;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NavigationBuilderTest extends TestCase
{
    /** @var NavigationBuilder - System Under Test */
    protected $sut;

    /** @var array */
    protected $sessionData = [
        'username' => 'jane',
    ];

    /** @var ButtonFactory|MockObject */
    protected $buttonFactoryMock;

    public function setUp(): void
    {
        $this->buttonFactoryMock = $this->createMock(ButtonFactory::class);

        $this->sut = new NavigationBuilder($this->buttonFactoryMock);
    }

    public function testHandleWithoutMatchingIntent()
    {
        /** @var Navigation|MockObject $navigationMock */
        $navigationMock = $this->createMock(Navigation::class);

        $event = new NavigationReady($navigationMock);

        $navigationMock->expects($this->once())->method('hasIntent')->willReturn(false);

        $this->sut->handle($event);
    }

    public function testHandleWithMatchingIntent()
    {
        /** @var Navigation|MockObject $navigationMock */
        $navigationMock = $this->createMock(Navigation::class);

        $event = new NavigationReady($navigationMock);

        $navigationMock->expects($this->atLeastOnce())->method('hasIntent')->willReturn(true);

        $this->buttonFactoryMock->expects($this->atLeastOnce())->method('createFromName')->willReturn(new Button());

        $this->sut->handle($event);
    }
}
