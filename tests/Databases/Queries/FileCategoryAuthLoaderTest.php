<?php

declare(strict_types=1);

namespace AbterPhp\Files\Databases\Queries;

use AbterPhp\Framework\Orm\DataMappers\SqlTestCase;

class FileCategoryAuthLoaderTest extends SqlTestCase
{
    /** @var FileCategoryAuthLoader */
    protected $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new FileCategoryAuthLoader($this->connectionPoolMock);
    }

    public function testLoadAll()
    {
        $userGroupIdentifier    = 'foo';
        $fileCategoryIdentifier = 'bar';

        $sql          = 'SELECT ug.identifier AS v0, fc.identifier AS v1 FROM user_groups_file_categories AS ugfc INNER JOIN file_categories AS fc ON ugfc.file_category_id = fc.id AND fc.deleted = 0 INNER JOIN user_groups AS ug ON ugfc.user_group_id = ug.id AND ug.deleted = 0'; // phpcs:ignore
        $valuesToBind = [];
        $returnValues = [
            [
                'v0' => $userGroupIdentifier,
                'v1' => $fileCategoryIdentifier,
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($valuesToBind, $returnValues));

        $actualResult = $this->sut->loadAll();

        $this->assertEquals($returnValues, $actualResult);
    }

    /**
     * @param array  $expectedData
     * @param object $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
    }
}
