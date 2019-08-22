<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Admin\TestCase\Orm\RepoTestCase;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Orm\DataMappers\FileSqlDataMapper;
use Opulence\Orm\DataMappers\IDataMapper;
use Opulence\Orm\IEntityRegistry;
use PHPUnit\Framework\MockObject\MockObject;

class FileRepoTest extends RepoTestCase
{
    /** @var FileRepo - System Under Test */
    protected $sut;

    /** @var FileSqlDataMapper|MockObject */
    protected $dataMapperMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new FileRepo($this->className, $this->dataMapperMock, $this->unitOfWorkMock);
    }

    /**
     * @return FileSqlDataMapper|MockObject
     */
    protected function createDataMapperMock(): IDataMapper
    {
        /** @var FileSqlDataMapper|MockObject $mock */
        $mock = $this->createMock(FileSqlDataMapper::class);

        return $mock;
    }

    public function testGetAll()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', '', '');
        $entityStub1 = new Entity('foo1', 'foo-1', 'Foo 1', '', '');
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getAll')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getAll();

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByIdFromCache()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', '', '');

        $entityRegistry = $this->createEntityRegistryStub($entityStub0);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->never())->method('getById');

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub0, $actualResult);
    }

    public function testGetByIdFromDataMapper()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', '', '');

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->once())->method('getById')->willReturn($entityStub0);

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub0, $actualResult);
    }

    public function testAdd()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', '', '');

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForInsertion')->with($entityStub0);

        $this->sut->add($entityStub0);
    }

    public function testDelete()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', '', '');

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForDeletion')->with($entityStub0);

        $this->sut->delete($entityStub0);
    }

    public function testGetPage()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', '', '');
        $entityStub1 = new Entity('foo1', 'foo-1', 'Foo 1', '', '');
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getPage')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByUser()
    {
        $userId = 'user-1';

        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', '', '');
        $entityStub1 = new Entity('foo1', 'foo-1', 'Foo 1', '', '');
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getByUserId')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        /** @var User|MockObject $userStub */
        $userStub = $this->createMock(User::class);
        $userStub->expects($this->any())->method('getId')->willReturn($userId);

        $actualResult = $this->sut->getByUser($userStub);

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByFilesystemName()
    {
        $fsName = 'fs-1';

        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', '', '');

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getByFilesystemName')->willReturn($entityStub0);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getByFilesystemName($fsName);

        $this->assertSame($entityStub0, $actualResult);
    }

    public function testGetPublicByFilesystemName()
    {
        $fsName = 'fs-1';

        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', '', '');

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getPublicByFilesystemName')->willReturn($entityStub0);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getPublicByFilesystemName($fsName);

        $this->assertSame($entityStub0, $actualResult);
    }

    public function testGetPublicByCategoryIdentifiers()
    {
        $categoryIdentifiers = ['fc-1', 'fc-2'];

        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0', '', '');
        $entityStub1 = new Entity('foo1', 'foo-1', 'Foo 1', '', '');
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getPublicByCategoryIdentifiers')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getPublicByCategoryIdentifiers($categoryIdentifiers);

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
