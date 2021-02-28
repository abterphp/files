<?php

declare(strict_types=1);

namespace AbterPhp\Files\Databases\Queries;

use AbterPhp\Admin\Exception\Database;
use AbterPhp\Framework\TestCase\Database\QueryTestCase;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;

class FileCategoryAuthLoaderTest extends QueryTestCase
{
    /** @var FileCategoryAuthLoader */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new FileCategoryAuthLoader($this->connectionPoolMock);
    }

    public function testLoadAll()
    {
        $userGroupIdentifier    = 'foo';
        $fileCategoryIdentifier = 'bar';

        $sql0         = 'SELECT ug.identifier AS v0, fc.identifier AS v1 FROM user_groups_file_categories AS ugfc INNER JOIN file_categories AS fc ON ugfc.file_category_id = fc.id AND fc.deleted_at IS NULL INNER JOIN user_groups AS ug ON ugfc.user_group_id = ug.id AND ug.deleted_at IS NULL'; // phpcs:ignore
        $valuesToBind = [];
        $returnValues = [
            [
                'v0' => $userGroupIdentifier,
                'v1' => $fileCategoryIdentifier,
            ],
        ];
        $statement0   = MockStatementFactory::createReadStatement($this, $valuesToBind, $returnValues);

        $this->readConnectionMock
            ->expects($this->once())
            ->method('prepare')
            ->with($sql0)
            ->willReturn($statement0);

        $actualResult = $this->sut->loadAll();

        $this->assertEquals($returnValues, $actualResult);
    }

    public function testLoadAllThrowsExceptionIfQueryFails()
    {
        $errorInfo = ['FOO123', 1, 'near AS v0, ar.identifier: hello'];

        $this->expectException(Database::class);
        $this->expectExceptionCode($errorInfo[1]);

        $sql0         = 'SELECT ug.identifier AS v0, fc.identifier AS v1 FROM user_groups_file_categories AS ugfc INNER JOIN file_categories AS fc ON ugfc.file_category_id = fc.id AND fc.deleted_at IS NULL INNER JOIN user_groups AS ug ON ugfc.user_group_id = ug.id AND ug.deleted_at IS NULL'; // phpcs:ignore
        $valuesToBind = [];
        $statement0   = MockStatementFactory::createErrorStatement($this, $valuesToBind, $errorInfo);

        $this->readConnectionMock
            ->expects($this->once())
            ->method('prepare')
            ->with($sql0)
            ->willReturn($statement0);

        $this->sut->loadAll();
    }
}
