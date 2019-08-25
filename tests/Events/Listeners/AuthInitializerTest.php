<?php

declare(strict_types=1);

namespace AbterPhp\Files\Events\Listeners;

use AbterPhp\Files\Authorization\FileCategoryProvider as AuthProvider;
use AbterPhp\Framework\Authorization\CombinedAdapter;
use AbterPhp\Framework\Events\AuthReady;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AuthInitializerTest extends TestCase
{
    /** @var AuthInitializer - System Under Test */
    protected $sut;

    /** @var AuthProvider|MockObject */
    protected $authProviderMock;

    public function setUp(): void
    {
        $this->authProviderMock = $this->createMock(AuthProvider::class);

        $this->sut = new AuthInitializer($this->authProviderMock);
    }

    public function testHandle()
    {
        $adapterMock = $this->createMock(CombinedAdapter::class);
        $adapterMock->expects($this->atLeastOnce())->method('registerAdapter')->with($this->authProviderMock);

        $event = new AuthReady($adapterMock);

        $this->sut->handle($event);
    }
}
