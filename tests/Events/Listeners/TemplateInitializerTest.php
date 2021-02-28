<?php

declare(strict_types=1);

namespace AbterPhp\Files\Events\Listeners;

use AbterPhp\Files\Template\Loader\File as FileLoader;
use AbterPhp\Framework\Events\TemplateEngineReady;
use AbterPhp\Framework\Template\Engine;
use AbterPhp\Framework\Template\Renderer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TemplateInitializerTest extends TestCase
{
    /** @var TemplateInitializer - System Under Test */
    protected $sut;

    /** @var FileLoader|MockObject */
    protected $fileLoaderMock;

    public function setUp(): void
    {
        $this->fileLoaderMock        = $this->createMock(FileLoader::class);

        $this->sut = new TemplateInitializer($this->fileLoaderMock);
    }

    public function testHandle()
    {
        $rendererMock = $this->createMock(Renderer::class);
        $rendererMock
            ->expects($this->once())
            ->method('addLoader')
            ->with(TemplateInitializer::TEMPLATE_TYPE, $this->fileLoaderMock)
            ->willReturnSelf();

        $engineMock = $this->createMock(Engine::class);
        $engineMock->expects($this->atLeastOnce())->method('getRenderer')->willReturn($rendererMock);

        $event = new TemplateEngineReady($engineMock);

        $this->sut->handle($event);
    }
}
