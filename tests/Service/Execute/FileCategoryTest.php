<?php

declare(strict_types=1);

namespace AbterPhp\Files\Service\Execute;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Files\Orm\FileCategoryRepo as GridRepo;
use AbterPhp\Files\Validation\Factory\FileCategory as ValidatorFactory;
use Cocur\Slugify\Slugify;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Orm\IUnitOfWork;
use Opulence\Validation\IValidator;
use Opulence\Validation\Rules\Errors\ErrorCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileCategoryTest extends TestCase
{
    /** @var FileCategory - System Under Test */
    protected $sut;

    /** @var GridRepo|MockObject */
    protected $gridRepoMock;

    /** @var ValidatorFactory|MockObject */
    protected $validatorFactoryMock;

    /** @var IUnitOfWork|MockObject */
    protected $unitOfWorkMock;

    /** @var IEventDispatcher|MockObject */
    protected $eventDispatcherMock;

    /** @var Slugify|MockObject */
    protected $slugifyMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->gridRepoMock         = $this->createMock(GridRepo::class);
        $this->validatorFactoryMock = $this->createMock(ValidatorFactory::class);
        $this->unitOfWorkMock       = $this->createMock(IUnitOfWork::class);
        $this->eventDispatcherMock  = $this->createMock(IEventDispatcher::class);
        $this->slugifyMock          = $this->createMock(Slugify::class);

        $this->sut = new FileCategory(
            $this->gridRepoMock,
            $this->validatorFactoryMock,
            $this->unitOfWorkMock,
            $this->eventDispatcherMock,
            $this->slugifyMock
        );
    }

    public function testCreateEntity()
    {
        $id = 'foo';

        $actualResult = $this->sut->createEntity($id);

        $this->assertInstanceOf(Entity::class, $actualResult);
        $this->assertSame($id, $actualResult->getId());
    }

    public function testCreate()
    {
        $identifier = 'bar';
        $postData   = [
            'identifier' => $identifier,
        ];

        $this->gridRepoMock->expects($this->once())->method('add');
        $this->eventDispatcherMock->expects($this->atLeastOnce())->method('dispatch');
        $this->unitOfWorkMock->expects($this->once())->method('commit');
        $this->slugifyMock->expects($this->any())->method('slugify')->willReturnArgument(0);

        /** @var IStringerEntity|Entity $actualResult */
        $actualResult = $this->sut->create($postData, []);

        $this->assertInstanceOf(Entity::class, $actualResult);
        $this->assertEmpty($actualResult->getId());
        $this->assertSame($identifier, $actualResult->getIdentifier());
    }

    public function testCreateWithUserGroups()
    {
        $identifier = 'bar';
        $ugId0      = '4567f007-6efa-4dae-b0af-795a3dbfe44e';
        $ugId1      = '66822f5c-e32c-434c-a220-552a41138653';
        $postData   = [
            'identifier'     => $identifier,
            'user_group_ids' => [$ugId0, $ugId1],
        ];

        $this->gridRepoMock->expects($this->once())->method('add');
        $this->eventDispatcherMock->expects($this->atLeastOnce())->method('dispatch');
        $this->unitOfWorkMock->expects($this->once())->method('commit');
        $this->slugifyMock->expects($this->any())->method('slugify')->willReturnArgument(0);

        /** @var IStringerEntity|Entity $actualResult */
        $actualResult = $this->sut->create($postData, []);

        $this->assertInstanceOf(Entity::class, $actualResult);
        $this->assertEmpty($actualResult->getId());
        $this->assertSame($identifier, $actualResult->getIdentifier());
    }

    public function testUpdate()
    {
        $id = '5c003d37-c59e-43eb-a471-e7b3c031fbeb';

        $entity = $this->sut->createEntity($id);

        $identifier = 'bar';
        $postData   = [
            'identifier' => $identifier,
        ];

        $this->gridRepoMock->expects($this->never())->method('add');
        $this->gridRepoMock->expects($this->never())->method('delete');
        $this->eventDispatcherMock->expects($this->atLeastOnce())->method('dispatch');
        $this->unitOfWorkMock->expects($this->once())->method('commit');
        $this->slugifyMock->expects($this->any())->method('slugify')->willReturnArgument(0);

        $actualResult = $this->sut->update($entity, $postData, []);

        $this->assertTrue($actualResult);
    }

    public function testUpdateThrowsExceptionWhenCalledWithWrongEntity()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var IStringerEntity|MockObject $entityStub */
        $entityStub = $this->createMock(IStringerEntity::class);

        $this->sut->update($entityStub, [], []);
    }

    public function testDelete()
    {
        $id     = 'foo';
        $entity = $this->sut->createEntity($id);

        $this->gridRepoMock->expects($this->once())->method('delete');
        $this->eventDispatcherMock->expects($this->atLeastOnce())->method('dispatch');
        $this->unitOfWorkMock->expects($this->once())->method('commit');

        $actualResult = $this->sut->delete($entity);

        $this->assertTrue($actualResult);
    }

    public function testRetrieveEntity()
    {
        $id     = 'foo';
        $entity = $this->sut->createEntity($id);

        $this->gridRepoMock->expects($this->once())->method('getById')->willReturn($entity);

        $actualResult = $this->sut->retrieveEntity($id);

        $this->assertSame($entity, $actualResult);
    }

    public function testRetrieveList()
    {
        $offset     = 0;
        $limit      = 2;
        $orders     = [];
        $conditions = [];
        $params     = [];

        $id0            = 'foo';
        $entity0        = $this->sut->createEntity($id0);
        $id1            = 'bar';
        $entity1        = $this->sut->createEntity($id1);
        $expectedResult = [$entity0, $entity1];

        $this->gridRepoMock->expects($this->once())->method('getPage')->willReturn($expectedResult);

        $actualResult = $this->sut->retrieveList($offset, $limit, $orders, $conditions, $params);

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testValidateFormSuccess()
    {
        $postData = ['foo' => 'bar'];

        $validatorMock = $this->createMock(IValidator::class);
        $validatorMock->expects($this->once())->method('isValid')->with($postData)->willReturn(true);
        $validatorMock->expects($this->never())->method('getErrors');

        $this->validatorFactoryMock->expects($this->once())->method('createValidator')->willReturn($validatorMock);

        $result = $this->sut->validateForm($postData);

        $this->assertSame([], $result);
    }

    public function testValidateFormFailure()
    {
        $postData = ['foo' => 'bar'];

        $errorsStub        = new ErrorCollection();
        $errorsStub['foo'] = ['foo error'];

        $validatorMock = $this->createMock(IValidator::class);
        $validatorMock->expects($this->once())->method('isValid')->with($postData)->willReturn(false);
        $validatorMock->expects($this->once())->method('getErrors')->willReturn($errorsStub);

        $this->validatorFactoryMock->expects($this->once())->method('createValidator')->willReturn($validatorMock);

        $result = $this->sut->validateForm($postData);

        $this->assertSame(['foo' => ['foo error']], $result);
    }

    public function testValidateCreatesOnlyOneValidator()
    {
        $postData = ['foo' => 'bar'];

        $validatorMock = $this->createMock(IValidator::class);
        $validatorMock->expects($this->any())->method('isValid')->with($postData)->willReturn(true);
        $validatorMock->expects($this->any())->method('getErrors');

        $this->validatorFactoryMock->expects($this->once())->method('createValidator')->willReturn($validatorMock);

        $firstRun  = $this->sut->validateForm($postData);
        $secondRun = $this->sut->validateForm($postData);

        $this->assertSame($firstRun, $secondRun);
    }
}
