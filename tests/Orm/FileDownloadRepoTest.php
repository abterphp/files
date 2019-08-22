<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Admin\TestCase\Orm\RepoTestCase;
use AbterPhp\Files\Domain\Entities\File;
use AbterPhp\Files\Domain\Entities\FileDownload as Entity;
use AbterPhp\Files\Orm\DataMappers\FileDownloadSqlDataMapper;
use Opulence\Orm\DataMappers\IDataMapper;
use Opulence\Orm\IEntityRegistry;
use PHPUnit\Framework\MockObject\MockObject;

class FileDownloadRepoTest extends RepoTestCase
{
    /** @var FileDownloadRepo - System Under Test */
    protected $sut;

    /** @var FileDownloadSqlDataMapper|MockObject */
    protected $dataMapperMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new FileDownloadRepo($this->className, $this->dataMapperMock, $this->unitOfWorkMock);
    }

    /**
     * @return FileDownloadSqlDataMapper|MockObject
     */
    protected function createDataMapperMock(): IDataMapper
    {
        /** @var FileDownloadSqlDataMapper|MockObject $mock */
        $mock = $this->createMock(FileDownloadSqlDataMapper::class);

        return $mock;
    }

    /**
     * @param string|int $postfix
     *
     * @return Entity
     */
    protected function createEntity($postfix): Entity
    {
        $userLanguage =  new UserLanguage("qux-$postfix", "qux-$postfix", "Qux$postfix");

        return new Entity(
            "foo-$postfix",
            new File("bar-$postfix", "foo-$postfix", "foo-$postfix", '', ''),
            new User("baz-$postfix", "baz-$postfix", 'baz0@example.com', '', false, false, $userLanguage),
            null
        );
    }

    public function testGetAll()
    {
        $entityStub0 = $this->createEntity('0');
        $entityStub1 = $this->createEntity('1');
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getAll')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getAll();

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByIdFromCache()
    {
        $entityStub0 = $this->createEntity('0');

        $entityRegistry = $this->createEntityRegistryStub($entityStub0);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->never())->method('getById');

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub0, $actualResult);
    }

    public function testGetByIdFromDataMapper()
    {
        $entityStub0 = $this->createEntity('0');

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->once())->method('getById')->willReturn($entityStub0);

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub0, $actualResult);
    }

    public function testAdd()
    {
        $entityStub0 = $this->createEntity('0');

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForInsertion')->with($entityStub0);

        $this->sut->add($entityStub0);
    }

    public function testDelete()
    {
        $entityStub0 = $this->createEntity('0');

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForDeletion')->with($entityStub0);

        $this->sut->delete($entityStub0);
    }

    public function testGetPage()
    {
        $entityStub0 = $this->createEntity('0');
        $entityStub1 = $this->createEntity('1');
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getPage')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByFile()
    {
        $entityStub0 = $this->createEntity('0');
        $entityStub1 = $this->createEntity('1');
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getByFileId')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getByFile($entityStub0->getFile());

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByUser()
    {
        $entityStub0 = $this->createEntity('0');
        $entityStub1 = $this->createEntity('1');
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getByUserId')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getByUser($entityStub0->getUser());

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
