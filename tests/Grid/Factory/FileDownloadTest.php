<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory;

use AbterPhp\Admin\Grid\Factory\GridFactory;
use AbterPhp\Admin\Grid\Factory\PaginationFactory;
use AbterPhp\Files\Domain\Entities\FileDownload as Entity;
use AbterPhp\Files\Grid\Factory\Table\FileDownload as TableFactory;
use AbterPhp\Files\Grid\Filters\FileDownload as Filters;
use AbterPhp\Framework\Grid\IGrid;
use AbterPhp\Framework\Helper\DateHelper;
use Opulence\Routing\Urls\UrlGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileDownloadTest extends TestCase
{
    /** @var FileDownload - System Under Test */
    protected $sut;

    /** @var MockObject|UrlGenerator */
    protected $urlGeneratorMock;

    /** @var MockObject|PaginationFactory */
    protected $paginationFactoryMock;

    /** @var MockObject|TableFactory */
    protected $tableFactoryMock;

    /** @var MockObject|GridFactory */
    protected $gridFactoryMock;

    /** @var MockObject|Filters */
    protected $filtersMock;

    public function setUp(): void
    {
        $this->urlGeneratorMock      = $this->createMock(UrlGenerator::class);
        $this->paginationFactoryMock = $this->createMock(PaginationFactory::class);
        $this->tableFactoryMock      = $this->createMock(TableFactory::class);
        $this->gridFactoryMock       = $this->createMock(GridFactory::class);
        $this->filtersMock           = $this->createMock(Filters::class);

        $this->sut = new FileDownload(
            $this->urlGeneratorMock,
            $this->paginationFactoryMock,
            $this->tableFactoryMock,
            $this->gridFactoryMock,
            $this->filtersMock
        );
    }

    public function testCreateGrid()
    {
        $params  = [];
        $baseUrl = '';

        $actualResult = $this->sut->createGrid($params, $baseUrl);

        $this->assertInstanceOf(IGrid::class, $actualResult);
    }

    public function testGetUploadedAtFormatsDateValue()
    {
        $dateStub = new \DateTime();

        $entityMock = $this->createMock(Entity::class);
        $entityMock->expects($this->any())->method('getDownloadedAt')->willReturn($dateStub);

        putenv('ADMIN_DATETIME_FORMAT=Y-m-d H:i:s');

        $actualResult = $this->sut->getDownloadedAt($entityMock);

        $this->assertSame(DateHelper::formatDateTime($dateStub), $actualResult);
    }
}
