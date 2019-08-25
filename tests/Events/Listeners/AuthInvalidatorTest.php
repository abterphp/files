<?php

declare(strict_types=1);

namespace AbterPhp\Files\Events\Listeners;

use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Framework\Authorization\CacheManager;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Events\EntityChange;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AuthInvalidatorTest extends TestCase
{
    /** @var AuthInvalidator - System Under Test */
    protected $sut;

    /** @var CacheManager|MockObject */
    protected $cacheManagerMock;

    public function setUp(): void
    {
        $this->cacheManagerMock = $this->createMock(CacheManager::class);

        $this->sut = new AuthInvalidator($this->cacheManagerMock);
    }

    public function testHandleWithGeneralEntity()
    {
        $entityTypeStub = 'bar';

        $eventMock = $this->createMock(EntityChange::class);
        $eventMock->expects($this->atLeastOnce())->method('getEntityName')->willReturn($entityTypeStub);

        $this->sut->handle($eventMock);
    }

    public function testHandleWithFileCategory()
    {
        $this->cacheManagerMock->expects($this->atLeastOnce())->method('clearAll');

        $eventMock = $this->createMock(EntityChange::class);
        $eventMock->expects($this->any())->method('getEntityName')->willReturn(FileCategory::class);

        $this->sut->handle($eventMock);
    }
}
