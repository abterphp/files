<?php

declare(strict_types=1);

namespace AbterPhp\Files\Form\Factory;

use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Files\Orm\FileCategoryRepo;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Sessions\ISession;
use Opulence\Sessions\Session;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    /** @var ISession|MockObject */
    protected $sessionMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    /** @var FileCategoryRepo|MockObject */
    protected $fileCategoryRepoMock;

    /** @var File */
    protected $sut;

    public function setUp()
    {
        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->setMethods(['get'])
            ->getMock();
        $this->sessionMock->expects($this->any())->method('get')->willReturnArgument(0);

        $this->translatorMock = $this->getMockBuilder(ITranslator::class)
            ->setMethods(['translate', 'canTranslate'])
            ->getMock();
        $this->translatorMock->expects($this->any())->method('translate')->willReturnArgument(0);

        $this->fileCategoryRepoMock = $this->getMockBuilder(FileCategoryRepo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAll'])
            ->getMock();

        $this->sut = new File($this->sessionMock, $this->translatorMock, $this->fileCategoryRepoMock);
    }

    public function testCreate()
    {
        $action            = 'foo';
        $method            = RequestMethods::POST;
        $showUrl           = 'bar';
        $entityId          = '59b927de-7fea-4866-97fb-2036d4fdbe2e';
        $identifier        = 'blah';
        $allFileCategories = [
            new FileCategory('a3e90fa1-3003-465d-82e0-570baa0aa53f', 'fc-22', 'FC 22', true, []),
            new FileCategory('bd40cbaf-29a9-4371-aa5d-e97966792c92', 'fc-73', 'FC 73', true, []),
            new FileCategory('544f7fac-38e1-4103-932f-452ea52733ed', 'fc-112', 'FC 112', false, []),
            new FileCategory('63c18995-d24a-41bb-9016-ebca473019e9', 'fc-432', 'FC 432', true, []),
        ];
        $fileCategory      = $allFileCategories[1];

        $this->fileCategoryRepoMock
            ->expects($this->any())
            ->method('getAll')
            ->willReturn($allFileCategories);

        $entityMock = $this->createMockEntity();

        $entityMock->expects($this->any())->method('getId')->willReturn($entityId);
        $entityMock->expects($this->any())->method('getDescription')->willReturn($identifier);
        $entityMock->expects($this->any())->method('getCategory')->willReturn($fileCategory);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entityMock);

        $this->assertContains($action, $form);
        $this->assertContains($showUrl, $form);
        $this->assertContains('CSRF', $form);
        $this->assertContains('POST', $form);
        $this->assertContains('file', $form);
        $this->assertContains('description', $form);
        $this->assertContains('file_category_id', $form);
        $this->assertContains('selected', $form);
        $this->assertContains('button', $form);
    }

    /**
     * @return MockObject|Entity
     */
    protected function createMockEntity()
    {
        $entityMock = $this->getMockBuilder(Entity::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getId',
                    'getDescription',
                    'getCategory',
                ]
            )
            ->getMock();

        return $entityMock;
    }
}
