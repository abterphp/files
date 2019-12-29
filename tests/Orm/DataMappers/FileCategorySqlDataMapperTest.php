<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm\DataMapper;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\TestCase\Orm\DataMapperTestCase;
use AbterPhp\Admin\TestDouble\Orm\MockIdGeneratorFactory;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Files\Orm\DataMappers\FileCategorySqlDataMapper;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;

class FileCategorySqlDataMapperTest extends DataMapperTestCase
{
    /** @var FileCategorySqlDataMapper */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new FileCategorySqlDataMapper($this->readConnectionMock, $this->writeConnectionMock);
    }

    public function testAddWithoutUserGroup()
    {
        $nextId     = '90962131-c7bc-40c3-8cdf-1a1e3b0936e4';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;

        $sql       = 'INSERT INTO file_categories (id, identifier, name, is_public) VALUES (?, ?, ?, ?)'; // phpcs:ignore
        $values    = [
            [$nextId, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$isPublic, \PDO::PARAM_BOOL],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement, 0);

        $entity = new FileCategory($nextId, $identifier, $name, $isPublic);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testAddWithUserGroup()
    {
        $nextId     = '2c86ba76-aadc-4463-a5f4-3d36c8f9e400';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;
        $ugfc0      = 'aba84d8d-fa47-4960-ae5e-3108b2329df6';
        $ugfc1      = '7d9755dc-a2b3-4149-b4c0-6b21b99dd524';
        $userGroups = [
            new UserGroup('90f489a6-7fd2-4cee-8e15-6e0a11dd5686', '', ''),
            new UserGroup('87c7f025-ad53-400c-8bf9-703fe02f88a0', '', ''),
        ];

        $this->sut->setIdGenerator(MockIdGeneratorFactory::create($this, $ugfc0, $ugfc1));

        $sql0       = 'INSERT INTO file_categories (id, identifier, name, is_public) VALUES (?, ?, ?, ?)'; // phpcs:ignore
        $values0    = [
            [$nextId, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$isPublic, \PDO::PARAM_BOOL],
        ];
        $statement0 = MockStatementFactory::createWriteStatement($this, $values0);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql0, $statement0, 0);

        $entity = new FileCategory($nextId, $identifier, $name, $isPublic, $userGroups);

        $sql1       = 'INSERT INTO user_groups_file_categories (id, user_group_id, file_category_id) VALUES (?, ?, ?)'; // phpcs:ignore
        $values1    = [
            [$ugfc0, \PDO::PARAM_STR],
            [$userGroups[0]->getId(), \PDO::PARAM_STR],
            [$nextId, \PDO::PARAM_STR],
        ];
        $statement1 = MockStatementFactory::createWriteStatement($this, $values1);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql1, $statement1, 1);

        $sql2       = 'INSERT INTO user_groups_file_categories (id, user_group_id, file_category_id) VALUES (?, ?, ?)'; // phpcs:ignore
        $values2    = [
            [$ugfc1, \PDO::PARAM_STR],
            [$userGroups[1]->getId(), \PDO::PARAM_STR],
            [$nextId, \PDO::PARAM_STR],
        ];
        $statement2 = MockStatementFactory::createWriteStatement($this, $values2);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql2, $statement2, 2);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id         = '42484052-b758-41f8-b55b-d61467596a3f';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;

        $sql0       = 'UPDATE file_categories AS file_categories SET deleted_at = ? WHERE (id = ?)'; // phpcs:ignore
        $statement0 = MockStatementFactory::createWriteStatementWithAny($this);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql0, $statement0, 0);

        $entity = new FileCategory($id, $identifier, $name, $isPublic);

        $sql1       = 'DELETE FROM user_groups_file_categories WHERE (file_category_id = ?)'; // phpcs:ignore
        $values1    = [[$id, \PDO::PARAM_STR]];
        $statement1 = MockStatementFactory::createWriteStatement($this, $values1);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql1, $statement1, 1);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = '72420a3e-0fb9-4017-9ad8-57d6f2e1d016';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;

        $sql          = 'SELECT fc.id, fc.identifier, fc.name, fc.is_public, GROUP_CONCAT(ugfc.user_group_id) AS user_group_ids FROM file_categories AS fc LEFT JOIN user_groups_file_categories AS ugfc ON ugfc.file_category_id = fc.id WHERE (fc.deleted_at IS NULL) GROUP BY fc.id'; // phpcs:ignore
        $values       = [];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'name' => $name, 'is_public' => $isPublic]];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetPage()
    {
        $id         = '72420a3e-0fb9-4017-9ad8-57d6f2e1d016';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS fc.id, fc.identifier, fc.name, fc.is_public, GROUP_CONCAT(ugfc.user_group_id) AS user_group_ids FROM file_categories AS fc LEFT JOIN user_groups_file_categories AS ugfc ON ugfc.file_category_id = fc.id WHERE (fc.deleted_at IS NULL) GROUP BY fc.id LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'name' => $name, 'is_public' => $isPublic]];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetPageWithOrdersAndConditions()
    {
        $id         = '72420a3e-0fb9-4017-9ad8-57d6f2e1d016';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;

        $orders     = ['fc.name ASC'];
        $conditions = ['fc.name LIKE \'abc%\'', 'fc.name LIKE \'%bca\''];

        $sql          = "SELECT SQL_CALC_FOUND_ROWS fc.id, fc.identifier, fc.name, fc.is_public, GROUP_CONCAT(ugfc.user_group_id) AS user_group_ids FROM file_categories AS fc LEFT JOIN user_groups_file_categories AS ugfc ON ugfc.file_category_id = fc.id WHERE (fc.deleted_at IS NULL) AND (fc.name LIKE 'abc%') AND (fc.name LIKE '%bca') GROUP BY fc.id ORDER BY fc.name ASC LIMIT 10 OFFSET 0"; // phpcs:ignore
        $values       = [];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'name' => $name, 'is_public' => $isPublic]];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getPage(0, 10, $orders, $conditions, []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id         = 'c44a45e8-67fb-4e96-85ff-88fb30d1c0e9';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;

        $sql          = 'SELECT fc.id, fc.identifier, fc.name, fc.is_public, GROUP_CONCAT(ugfc.user_group_id) AS user_group_ids FROM file_categories AS fc LEFT JOIN user_groups_file_categories AS ugfc ON ugfc.file_category_id = fc.id WHERE (fc.deleted_at IS NULL) AND (fc.id = :file_category_id) GROUP BY fc.id'; // phpcs:ignore
        $values       = ['file_category_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'name' => $name, 'is_public' => $isPublic]];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdWithUserGroups()
    {
        $id         = 'c44a45e8-67fb-4e96-85ff-88fb30d1c0e9';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;
        $ugId0      = '11c7ff36-3a00-447e-bcfe-594eee978ff7';
        $ugId1      = 'bc577876-3fa4-4bd8-833d-e52b9ff7b94d';

        $sql          = 'SELECT fc.id, fc.identifier, fc.name, fc.is_public, GROUP_CONCAT(ugfc.user_group_id) AS user_group_ids FROM file_categories AS fc LEFT JOIN user_groups_file_categories AS ugfc ON ugfc.file_category_id = fc.id WHERE (fc.deleted_at IS NULL) AND (fc.id = :file_category_id) GROUP BY fc.id'; // phpcs:ignore
        $values       = ['file_category_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'             => $id,
                'identifier'     => $identifier,
                'name'           => $name,
                'is_public'      => $isPublic,
                'user_group_ids' => "$ugId0,$ugId1",
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByUserGroupId()
    {
        $userGroupId = '11c7ff36-3a00-447e-bcfe-594eee978ff7';

        $id         = '3525f6d8-52ad-4bf2-ad68-15314ccff70d';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;
        $ugId0      = '11c7ff36-3a00-447e-bcfe-594eee978ff7';
        $ugId1      = 'bc577876-3fa4-4bd8-833d-e52b9ff7b94d';

        $sql          = 'SELECT fc.id, fc.identifier, fc.name, fc.is_public, GROUP_CONCAT(ugfc.user_group_id) AS user_group_ids FROM file_categories AS fc INNER JOIN user_groups_file_categories AS ugfc2 ON fc.id = ugfc2.file_category_id LEFT JOIN user_groups_file_categories AS ugfc ON ugfc.file_category_id = fc.id WHERE (fc.deleted_at IS NULL) AND (ugfc2.user_group_id = :user_group_id) GROUP BY fc.id'; // phpcs:ignore
        $values       = ['ugfc2.user_group_id' => [$userGroupId, \PDO::PARAM_STR]];
        $expectedData = [
            ['id'             => $id,
             'identifier'     => $identifier,
             'name'           => $name,
             'is_public'      => $isPublic,
             'user_group_ids' => "$ugId0,$ugId1",
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getByUserGroupId($userGroupId);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testUpdateWithoutUserGroup()
    {
        $id         = 'de8f969e-381e-4655-89db-46c8a7793bb3';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;

        $sql0       = 'UPDATE file_categories AS file_categories SET identifier = ?, name = ?, is_public = ? WHERE (id = ?) AND (deleted_at IS NULL)'; // phpcs:ignore
        $values0    = [
            [$identifier, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$isPublic, \PDO::PARAM_BOOL],
            [$id, \PDO::PARAM_STR],
        ];
        $statement0 = MockStatementFactory::createWriteStatement($this, $values0);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql0, $statement0, 0);

        $entity = new FileCategory($id, $identifier, $name, $isPublic);

        $sql1       = 'DELETE FROM user_groups_file_categories WHERE (file_category_id = ?)'; // phpcs:ignore
        $values1    = [
            [$id, \PDO::PARAM_STR],
        ];
        $statement1 = MockStatementFactory::createWriteStatement($this, $values1);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql1, $statement1, 1);

        $this->sut->update($entity);
    }

    public function testUpdateWithUserGroup()
    {
        $id         = 'a441487b-0bee-4137-8f76-c2a2b8d8c058';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;
        $ugfc0      = '6ac51550-d682-44b3-906e-0a8dac6f555f';
        $ugfc1      = '5791b3e6-18ce-4132-9ec1-d31a26a22c3d';
        $userGroups = [
            new UserGroup('4206761a-00f9-4285-8721-da7d2a1677bf', '', ''),
            new UserGroup('15e94e76-dc94-47fa-87f4-db97995d195e', '', ''),
        ];

        $this->sut->setIdGenerator(MockIdGeneratorFactory::create($this, $ugfc0, $ugfc1));

        $sql0       = 'UPDATE file_categories AS file_categories SET identifier = ?, name = ?, is_public = ? WHERE (id = ?) AND (deleted_at IS NULL)'; // phpcs:ignore
        $values0    = [
            [$identifier, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$isPublic, \PDO::PARAM_BOOL],
            [$id, \PDO::PARAM_STR],
        ];
        $statement0 = MockStatementFactory::createWriteStatement($this, $values0);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql0, $statement0, 0);

        $entity = new FileCategory($id, $identifier, $name, $isPublic, $userGroups);

        $sql1       = 'DELETE FROM user_groups_file_categories WHERE (file_category_id = ?)'; // phpcs:ignore
        $values1    = [
            [$id, \PDO::PARAM_STR],
        ];
        $statement1 = MockStatementFactory::createWriteStatement($this, $values1);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql1, $statement1, 1);

        $sql2       = 'INSERT INTO user_groups_file_categories (id, user_group_id, file_category_id) VALUES (?, ?, ?)'; // phpcs:ignore
        $values2    = [
            [$ugfc0, \PDO::PARAM_STR],
            [$userGroups[0]->getId(), \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $statement2 = MockStatementFactory::createWriteStatement($this, $values2);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql2, $statement2, 2);

        $sql3       = 'INSERT INTO user_groups_file_categories (id, user_group_id, file_category_id) VALUES (?, ?, ?)'; // phpcs:ignore
        $values3    = [
            [$ugfc1, \PDO::PARAM_STR],
            [$userGroups[1]->getId(), \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $statement3 = MockStatementFactory::createWriteStatement($this, $values3);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql3, $statement3, 3);

        $this->sut->update($entity);
    }

    /**
     * @param array        $expectedData
     * @param FileCategory $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(FileCategory::class, $entity);
        $this->assertEquals($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['name'], $entity->getName());
        $this->assertSame($expectedData['is_public'], $entity->isPublic());

        $this->assertUserGroups($expectedData, $entity);
    }

    /**
     * @param array        $expectedData
     * @param FileCategory $entity
     */
    protected function assertUserGroups(array $expectedData, $entity)
    {
        if (empty($expectedData['user_group_ids'])) {
            return;
        }

        $ugIds = [];
        foreach ($entity->getUserGroups() as $userGroup) {
            $ugIds[] = $userGroup->getId();
        }

        $this->assertSame($expectedData['user_group_ids'], implode(',', $ugIds));
    }
}
