<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\TestCase\Orm\RepoTestCase;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Files\Orm\DataMappers\FileCategorySqlDataMapper;
use Opulence\Orm\DataMappers\IDataMapper;
use Opulence\Orm\IEntityRegistry;
use PHPUnit\Framework\MockObject\MockObject;

class FileCategoryRepoTest extends RepoTestCase
{
    /** @var FileCategoryRepo - System Under Test */
    protected $sut;

    /** @var FileCategorySqlDataMapper|MockObject */
    protected $dataMapperMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new FileCategoryRepo($this->className, $this->dataMapperMock, $this->unitOfWorkMock);
    }

    /**
     * @return FileCategorySqlDataMapper|MockObject
     */
    protected function createDataMapperMock(): IDataMapper
    {
        /** @var FileCategorySqlDataMapper|MockObject $mock */
        $mock = $this->createMock(FileCategorySqlDataMapper::class);

        return $mock;
    }

    public function testGetAll()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', false);
        $entityStub1 = new Entity('foo1', 'foo-1', 'Foo 1', false);
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getAll')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getAll();

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByIdFromCache()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', false);

        $entityRegistry = $this->createEntityRegistryStub($entityStub0);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->never())->method('getById');

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub0, $actualResult);
    }

    public function testGetByIdFromDataMapper()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', false);

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->once())->method('getById')->willReturn($entityStub0);

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub0, $actualResult);
    }

    public function testAdd()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', false);

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForInsertion')->with($entityStub0);

        $this->sut->add($entityStub0);
    }

    public function testDelete()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', false);

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForDeletion')->with($entityStub0);

        $this->sut->delete($entityStub0);
    }

    public function testGetPage()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', false);
        $entityStub1 = new Entity('foo1', 'foo-1', 'Foo 1', false);
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getPage')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertSame($entities, $actualResult);
    }

    public function getByUserGroup()
    {
        $identifier = 'foo-0';

        $userGroup = new UserGroup('bar0', 'bar-0', 'Bar 0');
        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', false, [$userGroup]);
        $entityStub1 = new Entity('foo1', 'foo-1', 'Foo 1', false, [$userGroup]);
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getByUserGroupId')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getByUserGroup($identifier);

        $this->assertSame($entities, $actualResult);
    }

    /**
     * @param Entity|null $entity
     *
     * @return MockObject
     */
    protected function createEntityRegistryStub(?Entity $entity): MockObject
    {
        $entityRegistry = $this->createMock(IEntityRegistry::class);
        $entityRegistry->expects($this->any())->method('registerEntity');
        $entityRegistry->expects($this->any())->method('getEntity')->willReturn($entity);

        return $entityRegistry;
    }
}
